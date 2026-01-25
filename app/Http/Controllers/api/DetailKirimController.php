<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DetailKirim;
use App\Models\MasterKirim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailKirimController extends Controller
{
    public function index($kodekirim)
    {
        $masterkirim = MasterKirim::where('kodekirim', $kodekirim)
            ->with('detail.produk')
            ->first();

        if (!$masterkirim) {
            return response()->json([
                'message' => 'Data pengiriman tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data detail pengiriman.',
            'data' => $masterkirim
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodekirim' => 'required|exists:masterkirim,kodekirim',
            'kodeproduk' => 'required|exists:produk,kodeproduk',
            'qty' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            DetailKirim::create($validated);

            $total = DetailKirim::where('kodekirim', $validated['kodekirim'])->sum('qty');
            MasterKirim::where('kodekirim', $validated['kodekirim'])->update(['totalqty' => $total]);
        });

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $validated
        ], 201); // Created
    }

    public function update(Request $request, DetailKirim $detailKirim)
    {
        $validated = $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated, $detailKirim) {
            $detailKirim->update($validated);

            $total = DetailKirim::where('kodekirim', $detailKirim->kodekirim)->sum('qty');
            MasterKirim::where('kodekirim', $detailKirim->kodekirim)->update(['totalqty' => $total]);
        });

        return response()->json([
            'message' => 'Qty berhasil diupdate.',
            'data' => $detailKirim
        ], 200);
    }

    public function destroy(DetailKirim $detailKirim)
    {
        $kode = $detailKirim->kodekirim;

        DB::transaction(function () use ($detailKirim) {
            $detailKirim->delete();

            $total = DetailKirim::where('kodekirim', $detailKirim->kodekirim)->sum('qty');
            MasterKirim::where('kodekirim', $detailKirim->kodekirim)->update(['totalqty' => $total]);
        });

        return response()->json([
            'message' => 'Produk berhasil dihapus.',
            'kodekirim' => $kode
        ], 200);
    }
}

