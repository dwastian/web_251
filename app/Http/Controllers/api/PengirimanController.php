<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DetailKirim;
use App\Models\Kendaraan;
use App\Models\MasterKirim;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = MasterKirim::with('kendaraan')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return response()->json($pengiriman, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodekirim' => 'required|unique:masterkirim,kodekirim',
            'tglkirim' => 'required|date',
            'nopol' => 'required|exists:kendaraan,nopol',
            'catatan' => 'nullable|string|max:500',
            'produk' => 'nullable|array',
            'produk.*' => 'exists:produk,kodeproduk',
            'kuantitas' => 'nullable|array',
            'kuantitas.*' => 'integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $pengiriman = MasterKirim::create([
                'kodekirim' => $validated['kodekirim'],
                'tglkirim' => $validated['tglkirim'],
                'nopol' => $validated['nopol'],
                'status' => 'draft',
                'totalqty' => 0,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $totalQty = 0;

            if (!empty($validated['produk'])) {
                foreach ($validated['produk'] as $i => $kode) {
                    $qty = $validated['kuantitas'][$i] ?? 0;
                    if ($qty > 0) {
                        DetailKirim::create([
                            'kodekirim' => $pengiriman->kodekirim,
                            'kodeproduk' => $kode,
                            'qty' => $qty
                        ]);
                        $totalQty += $qty;
                    }
                }
            }

            $pengiriman->update(['totalqty' => $totalQty]);

            DB::commit();

            return response()->json([
                'message' => 'Pengiriman berhasil dibuat.',
                'data' => $pengiriman
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal membuat pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, MasterKirim $pengiriman)
    {
        $validated = $request->validate([
            'kodekirim' => ['required', \Illuminate\Validation\Rule::unique('masterkirim', 'kodekirim')->ignore($pengiriman->kodekirim, 'kodekirim')],
            'tglkirim' => 'required|date',
            'nopol' => 'required|exists:kendaraan,nopol',
            'catatan' => 'nullable|string|max:500',
            'produk' => 'nullable|array',
            'produk.*' => 'exists:produk,kodeproduk',
            'kuantitas' => 'nullable|array',
            'kuantitas.*' => 'integer|min:1',
            'action' => 'nullable|in:save_draft,confirm_save'
        ]);

        try {
            DB::beginTransaction();

            // 1. Delete existing details BEFORE updating master record
            // This avoids integrity constraint violations if kodekirim is changed
            $pengiriman->detailkirim()->delete();

            $totalQty = 0;
            if (isset($validated['produk'])) {
                foreach ($validated['produk'] as $i => $kode) {
                    $qty = $validated['kuantitas'][$i] ?? 0;
                    if ($qty > 0) {
                        DetailKirim::create([
                            'kodekirim' => $validated['kodekirim'], // Use the NEW kodekirim
                            'kodeproduk' => $kode,
                            'qty' => $qty
                        ]);
                        $totalQty += $qty;
                    }
                }
            }

            // 2. Update master record
            // Get raw status to avoid ucfirst accessor issues during update logic if needed
            $currentStatus = strtolower($pengiriman->getRawOriginal('status'));
            $newStatus = $validated['action'] == 'confirm_save' ? 'Confirmed' : $currentStatus;

            $pengiriman->update([
                'kodekirim' => $validated['kodekirim'],
                'tglkirim' => $validated['tglkirim'],
                'nopol' => $validated['nopol'],
                'catatan' => $validated['catatan'] ?? null,
                'status' => $newStatus,
                'totalqty' => $totalQty
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pengiriman berhasil diupdate.',
                'data' => $pengiriman
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal update pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function revertToDraft(MasterKirim $pengiriman)
    {
        try {
            $pengiriman->update(['status' => 'draft']);
            return response()->json([
                'message' => 'Status pengiriman berhasil dikembalikan ke Draft.',
                'data' => $pengiriman
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(MasterKirim $pengiriman)
    {
        try {
            DB::beginTransaction();
            // Delete details first
            $pengiriman->detailkirim()->delete();
            // Delete master
            $pengiriman->delete();
            DB::commit();

            return response()->json([
                'message' => 'Pengiriman berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getVehicleInfo($nopol)
    {
        $kendaraan = Kendaraan::find($nopol);

        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Data kendaraan.',
            'data' => $kendaraan
        ], 200);
    }

    public function savePengiriman(Request $request, MasterKirim $pengiriman)
    {
        $validated = $request->validate([
            'action' => 'required|in:save_draft,confirm_save,cancel'
        ]);

        try {
            DB::beginTransaction();

            switch ($validated['action']) {
                case 'save_draft':
                    $pengiriman->update(['status' => 'draft']);
                    $msg = 'Pengiriman disimpan sebagai draft.';
                    break;

                case 'confirm_save':
                    if ($pengiriman->detailkirim()->count() < 1) {
                        return response()->json([
                            'message' => 'Minimal 1 item diperlukan.'
                        ], 422);
                    }
                    $pengiriman->update(['status' => 'Confirmed']);
                    $msg = 'Pengiriman dikonfirmasi.';
                    break;

                case 'cancel':
                    if ($pengiriman->status === 'draft') {
                        $pengiriman->detailkirim()->delete();
                        $pengiriman->delete();
                        DB::commit();
                        return response()->json([
                            'message' => 'Pengiriman dibatalkan & dihapus.'
                        ]);
                    }
                    $msg = 'Pengiriman dibatalkan.';
                    break;
            }

            DB::commit();

            return response()->json([
                'message' => $msg,
                'data' => $pengiriman
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal proses pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'kodekirim' => 'required|exists:masterkirim,kodekirim',
            'kodeproduk' => 'required|exists:produk,kodeproduk',
            'qty' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $exist = DetailKirim::where('kodekirim', $validated['kodekirim'])
                    ->where('kodeproduk', $validated['kodeproduk'])
                    ->first();

                if ($exist) {
                    $exist->update(['qty' => $exist->qty + $validated['qty']]);
                } else {
                    DetailKirim::create($validated);
                }

                $total = DetailKirim::where('kodekirim', $validated['kodekirim'])->sum('qty');
                MasterKirim::where('kodekirim', $validated['kodekirim'])->update(['totalqty' => $total]);
            });

            return response()->json([
                'message' => 'Produk ditambahkan.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambah produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDetailQty(Request $request, DetailKirim $detail)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($detail, $validated) {
                $detail->update($validated);

                $total = DetailKirim::where('kodekirim', $detail->kodekirim)->sum('qty');
                MasterKirim::where('kodekirim', $detail->kodekirim)->update(['totalqty' => $total]);
            });

            return response()->json([
                'message' => 'Qty diupdate.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal update qty.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeDetail(DetailKirim $detail)
    {
        try {
            DB::transaction(function () use ($detail) {
                $kode = $detail->kodekirim;
                $detail->delete();

                $total = DetailKirim::where('kodekirim', $kode)->sum('qty');
                MasterKirim::where('kodekirim', $kode)->update(['totalqty' => $total]);
            });

            return response()->json([
                'message' => 'Item dihapus.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal hapus item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 400);
        }

        try {
            DB::beginTransaction();

            // Delete details first then master
            DetailKirim::whereIn('kodekirim', $ids)->delete();
            MasterKirim::whereIn('kodekirim', $ids)->delete();

            DB::commit();

            return response()->json([
                'message' => count($ids) . ' pengiriman berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus beberapa pengiriman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
