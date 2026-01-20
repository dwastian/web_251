<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailKirimController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MasterKirimController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Resource routes
Route::resource('produk', ProdukController::class);
Route::resource('gudang', GudangController::class);
Route::resource('kendaraan', KendaraanController::class);
Route::resource('pengiriman', PengirimanController::class);

// AJAX endpoints for pengiriman
Route::get('pengiriman/get-vehicle-info/{nopol}', [PengirimanController::class, 'getVehicleInfo']);
Route::post('pengiriman/add-product', [PengirimanController::class, 'addProduct']);
Route::put('pengiriman/update-detail-qty/{detail}', [PengirimanController::class, 'updateDetailQty']);
Route::delete('pengiriman/remove-detail/{detail}', [PengirimanController::class, 'removeDetail']);
Route::post('pengiriman/{pengiriman}/save-pengiriman', [PengirimanController::class, 'savePengiriman']);
Route::get('produk/get-produk/{id}', [ProdukController::class, 'getProduk']);

// Detail Kirim hanya untuk store, update, destroy
Route::resource('detailkirim', DetailKirimController::class)->only(['store', 'update', 'destroy']);

// Pengiriman → Detail (custom route untuk melihat detail dari master)
Route::get('pengiriman/{kodekirim}/detail', [DetailKirimController::class, 'index'])->name('pengiriman.detail.index');
