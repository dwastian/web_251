<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailKirimController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MasterKirimController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('produk', ProdukController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('kendaraan', KendaraanController::class);
    Route::resource('pengiriman', PengirimanController::class);
    Route::resource('detailkirim', DetailKirimController::class)->only(['store', 'update', 'destroy']);
});
