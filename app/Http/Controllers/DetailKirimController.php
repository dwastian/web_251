<?php

namespace App\Http\Controllers;

use App\Models\DetailKirim;
use App\Models\MasterKirim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailKirimController extends Controller
{
    public function index($kodekirim)
    {
        $masterkirim = MasterKirim::where('kodekirim', $kodekirim)->with('detail.produk')->firstOrFail();

        return view('masterkirim.show', compact('masterkirim'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodekirim' => 'required|exists:masterkirim,kodekirim',
            'kodeproduk' => 'required|exists:produk,kodeproduk',
            'qty' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {
            DetailKirim::create($request->all());

            // hitung ulang total qty
            $total = DetailKirim::where('kodekirim', $request->kodekirim)->sum('qty');
            MasterKirim::where('kodekirim', $request->kodekirim)->update(['totalqty' => $total]);
        });

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, DetailKirim $detailKirim)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request, $detailKirim) {
            $detailKirim->update($request->all());

            // hitung ulang total qty
            $total = DetailKirim::where('kodekirim', $detailKirim->kodekirim)->sum('qty');
            MasterKirim::where('kodekirim', $detailKirim->kodekirim)->update(['totalqty' => $total]);
        });

        return back()->with('success', 'Qty berhasil diupdate.');
    }

    public function destroy(DetailKirim $detailKirim)
    {
        $kode = $detailKirim->kodekirim;

        DB::transaction(function () use ($detailKirim) {
            $detailKirim->delete();

            // hitung ulang setelah delete
            $total = DetailKirim::where('kodekirim', $detailKirim->kodekirim)->sum('qty');
            MasterKirim::where('kodekirim', $detailKirim->kodekirim)->update(['totalqty' => $total]);
        });

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
