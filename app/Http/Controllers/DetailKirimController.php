<?php

namespace App\Http\Controllers;

use App\Models\DetailKirim;
use App\Models\MasterKirim;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailKirimController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kodekirim' => 'required|exists:masterkirim,kodekirim',
            'kodeproduk' => 'required|exists:produk,kodeproduk',
            'qty' => 'required|numeric|min:1',
        ]);

        DetailKirim::create($request->all());

        // hitung ulang total qty
        $total = DetailKirim::where('kodekirim', $request->kodekirim)->sum('qty');
        MasterKirim::where('kodekirim', $request->kodekirim)->update(['totalqty' => $total]);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, DetailKirim $detailKirim)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        $detailKirim->update($request->all());

        // hitung ulang total qty
        $total = DetailKirim::where('kodekirim', $detailKirim->kodekirim)->sum('qty');
        MasterKirim::where('kodekirim', $detailKirim->kodekirim)->update(['totalqty' => $total]);

        return back()->with('success', 'Qty berhasil diupdate.');
    }

    public function destroy(DetailKirim $detailKirim)
    {
        $kode = $detailKirim->kodekirim;
        $detailKirim->delete();

        // hitung ulang setelah delete
        $total = DetailKirim::where('kodekirim', $kode)->sum('qty');
        MasterKirim::where('kodekirim', $kode)->update(['totalqty' => $total]);

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}

