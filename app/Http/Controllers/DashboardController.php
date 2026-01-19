<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\MasterKirim;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'countProduk' => Produk::count(),
            'countGudang' => Gudang::count(),
            'countKendaraan' => Kendaraan::count(),
            'countKirim' => MasterKirim::count(),
        ]);
    }
}
