<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\ManajemenController as AdminManajemenController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController;
use App\Http\Controllers\Admin\PengeluaranController as AdminPengeluaranController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\OrderController as KasirOrderController;
use App\Http\Controllers\Kasir\PelangganController as KasirPelangganController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksiController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/penjualan', [AdminLaporanController::class, 'penjualan'])->name('admin.laporan.penjualan');
        Route::get('/piutang', [AdminLaporanController::class, 'piutang'])->name('admin.laporan.piutang');
        Route::get('/performa', [AdminLaporanController::class, 'performa'])->name('admin.laporan.performa');
        Route::get('/pengeluaran', [AdminLaporanController::class, 'pengeluaran'])->name('admin.laporan.pengeluaran');
        
        Route::get('/penjualan/export/{format}', [AdminLaporanController::class, 'exportPenjualan'])->name('admin.laporan.penjualan.export');
    });
    
    // Pengeluaran
    Route::prefix('pengeluaran')->group(function () {
        Route::get('/input', [AdminPengeluaranController::class, 'create'])->name('admin.pengeluaran.create');
        Route::post('/store', [AdminPengeluaranController::class, 'store'])->name('admin.pengeluaran.store');
        Route::get('/edit/{id}', [AdminPengeluaranController::class, 'edit'])->name('admin.pengeluaran.edit');
        Route::put('/update/{id}', [AdminPengeluaranController::class, 'update'])->name('admin.pengeluaran.update');
        Route::delete('/delete/{id}', [AdminPengeluaranController::class, 'destroy'])->name('admin.pengeluaran.delete');
    });
    
    // Manajemen
    Route::prefix('manajemen')->group(function () {
        Route::get('/paket', [AdminManajemenController::class, 'paket'])->name('admin.manajemen.paket');
        Route::post('/paket/simpan', [AdminManajemenController::class, 'simpanPaket'])->name('admin.manajemen.paket.simpan');
        Route::post('/paket/update/{id}', [AdminManajemenController::class, 'updatePaket'])->name('admin.manajemen.paket.update');
        Route::post('/paket/toggle/{id}', [AdminManajemenController::class, 'togglePaket'])->name('admin.manajemen.paket.toggle');
        
        Route::get('/karyawan', [AdminManajemenController::class, 'karyawan'])->name('admin.manajemen.karyawan');
        Route::post('/karyawan/simpan', [AdminManajemenController::class, 'simpanKaryawan'])->name('admin.manajemen.karyawan.simpan');
        Route::post('/karyawan/reset-password/{id}', [AdminManajemenController::class, 'resetPassword'])->name('admin.manajemen.karyawan.reset');
        Route::delete('/karyawan/hapus/{id}', [AdminManajemenController::class, 'hapusKaryawan'])->name('admin.manajemen.karyawan.hapus');
        
        Route::get('/pelanggan', [AdminPelangganController::class, 'index'])->name('admin.manajemen.pelanggan');
        Route::post('/pelanggan/update/{id}', [AdminPelangganController::class, 'update'])->name('admin.manajemen.pelanggan.update');
        Route::delete('/pelanggan/hapus/{id}', [AdminPelangganController::class, 'hapus'])->name('admin.manajemen.pelanggan.hapus');
    });
});

// Kasir Routes
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('kasir.dashboard');
    
    // Order
    Route::prefix('order')->group(function () {
        Route::get('/baru', [KasirOrderController::class, 'baru'])->name('kasir.order.baru');
        Route::post('/cari-pelanggan', [KasirOrderController::class, 'cariPelanggan'])->name('kasir.order.cari.pelanggan');
        Route::post('/simpan-pelanggan', [KasirOrderController::class, 'simpanPelanggan'])->name('kasir.order.simpan.pelanggan');
        Route::post('/simpan-transaksi', [KasirOrderController::class, 'simpanTransaksi'])->name('kasir.order.simpan.transaksi');
        
        Route::get('/list', [KasirOrderController::class, 'list'])->name('kasir.order.list');
        Route::get('/detail/{id}', [KasirOrderController::class, 'detail'])->name('kasir.order.detail');
        Route::post('/update-status/{id}', [KasirOrderController::class, 'updateStatus'])->name('kasir.order.update.status');
    });
    
    // Pelanggan
    Route::prefix('pelanggan')->group(function () {
        Route::get('/list', [KasirPelangganController::class, 'index'])->name('kasir.pelanggan.list');
        Route::get('/tambah', [KasirPelangganController::class, 'tambah'])->name('kasir.pelanggan.tambah');
        Route::post('/simpan', [KasirPelangganController::class, 'simpan'])->name('kasir.pelanggan.simpan');
        Route::get('/fix', [KasirPelangganController::class, 'fixMissingBranch'])->name('kasir.pelanggan.fix');
    });
    
    // Transaksi
    Route::prefix('transaksi')->group(function () {
        Route::post('/lunas/{id}', [KasirTransaksiController::class, 'lunas'])->name('kasir.transaksi.lunas');
        Route::post('/selesai/{id}', [KasirTransaksiController::class, 'selesai'])->name('kasir.transaksi.selesai');
    });
});

// Home Redirect based on Role
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('kasir.dashboard');
        }
    }
    return redirect()->route('login');
});