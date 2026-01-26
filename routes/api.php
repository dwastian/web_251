<?php

use App\Http\Controllers\api\DetailKirimController;
use App\Http\Controllers\api\GudangController;
use App\Http\Controllers\api\KendaraanController;
use App\Http\Controllers\api\PengirimanController;
use App\Http\Controllers\api\ProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// AJAX endpoints for pengiriman
Route::group(["prefix" => "/pengiriman"], function () {
    Route::get('/', [PengirimanController::class, 'index']);
    Route::post('/', [PengirimanController::class, 'store']);
    Route::put('/update/{pengiriman}', [PengirimanController::class, 'update']);
    Route::get('/{kodekirim}/detail', [DetailKirimController::class, 'index']);
    Route::post('/{pengiriman}/save-pengiriman', [PengirimanController::class, 'savePengiriman']);
    Route::put('/update-status/{pengiriman}/revert-to-draft', [PengirimanController::class, 'revertToDraft']);
    Route::post('/add-product', [PengirimanController::class, 'addProduct']);
    Route::get('/get-vehicle-info/{nopol}', [PengirimanController::class, 'getVehicleInfo']);
    Route::put('/update-detail-qty/{detail}', [PengirimanController::class, 'updateDetailQty']);
    Route::delete('/remove-detail/{detail}', [PengirimanController::class, 'removeDetail']);
    Route::delete('/{pengiriman}', [PengirimanController::class, 'destroy']);
    Route::post('/bulk-delete', [PengirimanController::class, 'bulkDestroy']);
});

Route::group(['prefix' => '/produk'], function () {
    Route::get('/', [ProdukController::class, 'index']);
    Route::post('/', [ProdukController::class, 'store']);
    Route::get('/{produk}', [ProdukController::class, 'show']);
    Route::post('/update/{produk}', [ProdukController::class, 'update']);
    Route::delete('/{produk}', [ProdukController::class, 'destroy']);
    Route::get('/get-produk/{id}', [ProdukController::class, 'getProduk']);
    Route::post('/bulk-delete', [ProdukController::class, 'bulkDestroy']);
});

Route::group(['prefix' => '/kendaraan'], function () {
    Route::get('/', [KendaraanController::class, 'index']);
    Route::post('/', [KendaraanController::class, 'store']);
    Route::get('/{kendaraan}', [KendaraanController::class, 'show']);
    Route::post('/update/{kendaraan}', [KendaraanController::class, 'update']); // Using POST for potential multipart/form-data with file upload
    Route::delete('/{kendaraan}', [KendaraanController::class, 'destroy']);
    Route::post('/bulk-delete', [KendaraanController::class, 'bulkDestroy']);
});

Route::group(['prefix' => '/gudang'], function () {
    Route::get('/', [GudangController::class, 'index']);
    Route::post('/', [GudangController::class, 'store']);
    Route::get('/{gudang}', [GudangController::class, 'show']);
    Route::put('/{gudang}', [GudangController::class, 'update']);
    Route::delete('/{gudang}', [GudangController::class, 'destroy']);
    Route::post('/bulk-delete', [GudangController::class, 'bulkDestroy']);
});
