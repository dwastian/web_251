<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\MasterKirimController;
use App\Http\Controllers\DetailKirimController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Resource routes
Route::resource('produk', ProdukController::class);
Route::resource('gudang', GudangController::class);
Route::resource('kendaraan', KendaraanController::class);
Route::resource('masterkirim', MasterKirimController::class);

// Detail Kirim hanya untuk store, update, destroy
Route::resource('detailkirim', DetailKirimController::class)->only(['store','update','destroy']);

// Master → Detail (custom route untuk melihat detail dari master)
Route::get('masterkirim/{kodekirim}/detail', [DetailKirimController::class, 'index'])->name('detail.index');
