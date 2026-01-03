<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. GUEST: Rute sebelum login (Hanya bisa diakses jika belum masuk)
Route::middleware('guest')->group(function () {
    // Menampilkan halaman login sesuai folder resources/views/auth/login.blade.php
    Route::get('/', function () { 
        return view('auth.login'); 
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// 2. AUTH: Rute setelah login (Harus login untuk akses)
Route::middleware('auth')->group(function () {
    
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- KELOMPOK: KESISWAAN (Admin Utama) ---
    // Semua URL di bawah ini akan diawali dengan /kesiswaan/
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {
        
        // Dashboard Utama
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('kesiswaan.dashboard');

        // Manajemen Santriwati (Sesuai Mockup Fix)
        Route::prefix('santri')->group(function () {
            Route::get('/', [DataMasterController::class, 'index'])->name('santri.index');
            Route::get('/tambah', [DataMasterController::class, 'create'])->name('santri.create');
            Route::post('/simpan', [DataMasterController::class, 'store'])->name('santri.store');
            Route::get('/{id}/edit', [DataMasterController::class, 'edit'])->name('santri.edit');
            Route::put('/{id}/update', [DataMasterController::class, 'update'])->name('santri.update');
            Route::delete('/{id}/hapus', [DataMasterController::class, 'destroy'])->name('santri.destroy');

        });

        // Fitur Lainnya (Jadwal, Skor, dll sesuai Sidebar)
        Route::get('/jadwal', function() { return "Halaman Jadwal"; })->name('kesiswaan.jadwal');
        Route::get('/skor', function() { return "Halaman Skor"; })->name('kesiswaan.skor');
        Route::get('/log', function() { return "Halaman Log Aktivitas"; })->name('kesiswaan.log');
    });

});