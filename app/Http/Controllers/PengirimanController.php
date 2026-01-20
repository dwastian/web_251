<?php

namespace App\Http\Controllers;

use App\Models\DetailKirim;
use App\Models\Kendaraan;
use App\Models\MasterKirim;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    /**
     * Display a listing of resource.
     */
    public function index()
    {
        $pengiriman = MasterKirim::with('kendaraan')->latest()->get();

        return view('pengiriman.index', compact('pengiriman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kendaraan = Kendaraan::orderBy('namakendaraan')->get();
        $produk = Produk::orderBy('nama')->get();

        return view('pengiriman.create', compact('kendaraan', 'produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $action = $request->input('submit_form', 'create');

        if ($action === 'create') {
            $request->validate([
                'kodekirim' => 'required|unique:pengiriman,kodekirim',
                'tglkirim' => 'required|date',
                'nopol' => 'required|exists:kendaraan,nopol',
                'catatan' => 'nullable|string|max:500',
                'produk' => 'required|array|min:1',
                'produk.*' => 'required|exists:produk,kodeproduk',
                'kuantitas' => 'required|array|min:1',
                'kuantitas.*' => 'required|integer|min:1',
            ]);
        } else {
            // For other actions, validate differently
            $request->validate([
                'kodekirim' => 'required|unique:pengiriman,kodekirim',
                'tglkirim' => 'required|date',
                'nopol' => 'required|exists:kendaraan,nopol',
                'catatan' => 'nullable|string|max:500',
            ]);
        }

        try {
            DB::beginTransaction();

            $pengiriman = MasterKirim::create([
                'kodekirim' => $request->kodekirim,
                'tglkirim' => $request->tglkirim,
                'nopol' => $request->nopol,
                'totalqty' => 0,
                'status' => 'draft',
                'catatan' => $request->catatan,
            ]);

            // Process product details
            $totalQty = 0;
            if ($request->has('produk') && is_array($request->produk)) {
                foreach ($request->produk as $index => $kodeproduk) {
                    $qty = $request->kuantitas[$index] ?? 0;
                    if ($kodeproduk && $qty > 0) {
                        DetailKirim::create([
                            'kodekirim' => $pengiriman->kodekirim,
                            'kodeproduk' => $kodeproduk,
                            'qty' => $qty,
                        ]);
                        $totalQty += $qty;
                    }
                }
            }

            // Update total quantity
            $pengiriman->update(['totalqty' => $totalQty]);

            DB::commit();
            return redirect()->route('pengiriman.edit', $pengiriman->kodekirim)
                        ->with('success', 'Pengiriman berhasil dibuat. Silakan tambahkan detail barang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pengiriman: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterKirim $pengiriman)
    {
        $pengiriman->load(['kendaraan', 'detailkirim.produk']);

        return view('pengiriman.show', compact('pengiriman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterKirim $pengiriman)
    {
        $pengiriman->load(['kendaraan', 'detailkirim.produk']);
        $kendaraan = Kendaraan::orderBy('namakendaraan')->get();

        return view('pengiriman.edit', compact('pengiriman', 'kendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterKirim $pengiriman)
    {
        $request->validate([
            'kodekirim' => 'required|unique:masterkirim,kodekirim,' . $pengiriman->kodekirim . ',kodekirim',
            'tglkirim' => 'required|date',
            'nopol' => 'required|exists:kendaraan,nopol',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pengiriman->update($request->all());

            DB::commit();
            return redirect()->route('pengiriman.edit', $pengiriman->kodekirim)
                        ->with('success', 'Pengiriman berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update pengiriman: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterKirim $pengiriman)
    {
        if ($pengiriman->detailkirim()->exists()) {
            return redirect()->route('pengiriman.index')->with('error', 'Pengiriman tidak dapat dihapus karena masih memiliki detail pengiriman.');
        }

        $pengiriman->delete();

        return redirect()->route('pengiriman.index')->with('success', 'Pengiriman berhasil dihapus.');
    }

    /**
     * Get vehicle info for AJAX
     */
    public function getVehicleInfo($nopol)
    {
        $kendaraan = Kendaraan::find($nopol);

        if (! $kendaraan) {
            return response()->json(['error' => 'Kendaraan tidak ditemukan'], 404);
        }

        return response()->json([
            'namadriver' => $kendaraan->namadriver,
            'kontakdriver' => $kendaraan->kontakdriver,
            'namakendaraan' => $kendaraan->namakendaraan,
            'jeniskendaraan' => $kendaraan->jeniskendaraan,
            'kapasitas' => $kendaraan->kapasitas,
            'tahun' => $kendaraan->tahun,
        ]);
    }

    /**
     * Save pengiriman (final confirmation)
     */
    public function savePengiriman(Request $request, ?MasterKirim $pengiriman = null)
    {
        $request->validate([
            'action' => 'required|in:save_draft,confirm_save,cancel',
        ]);

        try {
            DB::beginTransaction();

            switch ($request->action) {
                case 'save_draft':
                    if ($pengiriman) {
                        $pengiriman->update(['status' => 'draft']);
                        $message = 'Pengiriman berhasil disimpan sebagai draft.';
                    } else {
                        throw new \Exception('Pengiriman tidak ditemukan.');
                    }
                    break;

                case 'confirm_save':
                    // Validasi minimal 1 item
                    if ($pengiriman && $pengiriman->detailkirim()->count() < 1) {
                        throw new \Exception('Pengiriman harus memiliki minimal 1 item barang.');
                    }

                    $pengiriman->update(['status' => 'confirmed']);
                    $message = 'Pengiriman berhasil dikonfirmasi dan disimpan.';
                    break;

                case 'cancel':
                    // Hapus detail items jika draft
                    if ($pengiriman && $pengiriman->status === 'draft') {
                        $pengiriman->detailkirim()->delete();
                        $pengiriman->delete();

                        return redirect()->route('pengiriman.index')
                            ->with('info', 'Pengiriman dibatalkan.');
                    }
                    $message = 'Pengiriman dibatalkan.';
                    break;

                default:
                    throw new \Exception('Action tidak valid.');
            }

            DB::commit();

            return redirect()->route('pengiriman.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add product to pengiriman (AJAX)
     */
    public function addProduct(Request $request)
    {
        $request->validate([
            'kodekirim' => 'required|exists:masterkirim,kodekirim',
            'kodeproduk' => 'required|exists:produk,kodeproduk',
            'qty' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Check if product already exists in this pengiriman
                $existingDetail = DetailKirim::where('kodekirim', $request->kodekirim)
                    ->where('kodeproduk', $request->kodeproduk)
                    ->first();

                if ($existingDetail) {
                    // Update existing quantity
                    $newQty = $existingDetail->qty + $request->qty;
                    $existingDetail->update(['qty' => $newQty]);
                } else {
                    // Add new detail
                    DetailKirim::create($request->all());
                }

                // Recalculate total quantity
                $total = DetailKirim::where('kodekirim', $request->kodekirim)->sum('qty');
                MasterKirim::where('kodekirim', $request->kodekirim)->update(['totalqty' => $total]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'total_qty' => DetailKirim::where('kodekirim', $request->kodekirim)->sum('qty'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah produk: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update detail quantity (AJAX)
     */
    public function updateDetailQty(Request $request, DetailKirim $detail)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $detail) {
                $detail->update(['qty' => $request->qty]);

                // Recalculate total
                $total = DetailKirim::where('kodekirim', $detail->kodekirim)->sum('qty');
                MasterKirim::where('kodekirim', $detail->kodekirim)->update(['totalqty' => $total]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diupdate.',
                'total_qty' => DetailKirim::where('kodekirim', $detail->kodekirim)->sum('qty'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update kuantitas: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove detail item (AJAX)
     */
    public function removeDetail(DetailKirim $detail)
    {
        try {
            DB::transaction(function () use ($detail) {
                $kodekirim = $detail->kodekirim;
                $detail->delete();

                // Recalculate total
                $total = DetailKirim::where('kodekirim', $kodekirim)->sum('qty');
                MasterKirim::where('kodekirim', $kodekirim)->update(['totalqty' => $total]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus.',
                'total_qty' => DetailKirim::where('kodekirim', $detail->kodekirim)->sum('qty'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus item: '.$e->getMessage(),
            ], 500);
        }
    }
}
