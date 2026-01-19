<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\MasterKirim;
use Illuminate\Http\Request;

class MasterKirimController extends Controller
{
    public function index()
    {
        $masterKirim = MasterKirim::all();

        return view('masterkirim.index', compact('masterKirim'));
    }

    public function create()
    {
        $kendaraan = Kendaraan::orderBy('namakendaraan')->get();

        return view('masterkirim.create', compact('kendaraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodekirim' => 'required|unique:masterkirim,kodekirim',
            'tglkirim' => 'required|date',
            'nopol' => 'required|exists:kendaraan,nopol',
        ]);

        MasterKirim::create([
            'kodekirim' => $request->kodekirim,
            'tglkirim' => $request->tglkirim,
            'nopol' => $request->nopol,
            'totalqty' => 0,
        ]);

        // langsung ke halaman detail transaksi
        return redirect()->route('masterkirim.show', $request->kodekirim);
    }

    public function show(MasterKirim $masterkirim)
    {
        $masterkirim->load('detail.produk');

        return view('masterkirim.show', compact('masterkirim'));
    }

    public function destroy(MasterKirim $masterkirim)
    {
        if ($masterkirim->detail()->exists()) {
            return back()->with('error', 'Master kirim tidak dapat dihapus karena masih memiliki detail pengiriman.');
        }

        $masterkirim->delete();

        return back()->with('success', 'Transaksi berhasil dihapus');
    }
}
