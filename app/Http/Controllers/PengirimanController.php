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
}
