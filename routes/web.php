<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. GUEST: Rute sebelum login
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 2. AUTH: Rute setelah login
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- KELOMPOK: KESISWAAN (Admin Utama) ---
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('kesiswaan.dashboard');
        
        // CRUD Santri & User (FR-05, 09)
        Route::resource('santri', DataMasterController::class);
        
        // CRUD Kegiatan (FR-02)
        Route::resource('kegiatan', KegiatanController::class);
        
        // Log System (FR-06)
        Route::get('/logs', [LogController::class, 'index']);
        
        // Export Laporan (FR-04) - Link yang dicari Test tc14-16
        Route::get('/export-presensi', [PresensiController::class, 'export']);
    });

    // --- KELOMPOK: KOMDIS ---
    Route::middleware('role:KOMDIS')->prefix('komdis')->group(function () {
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('komdis.dashboard');
        
        // Operasional Scan RFID (FR-07) - Link yang dicari Test tc07-10
        Route::get('/scan', [PresensiController::class, 'scanView']);
        Route::post('/scan', [PresensiController::class, 'store']); 
        
        // CRUD Kegiatan untuk KOMDIS (FR-08)
        Route::resource('kegiatan', KegiatanController::class);
    });

    // --- KELOMPOK: MUSYRIFAH (Wali Kelas) ---
    Route::middleware('role:Musyrifah')->prefix('musyrifah')->group(function () {
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('musyrifah.dashboard');
        
        // Monitoring Presensi Kelas (FR-11)
        Route::get('/monitoring', [PresensiController::class, 'index']);
        
        // CRUD Penilaian Kedisiplinan (FR-12)
        Route::resource('penilaian', PenilaianController::class);
    });

    // --- KELOMPOK: SANTRIWATI (User) ---
    Route::middleware('role:Santriwati')->prefix('santri')->group(function () {
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('santri.dashboard');
        
        // Riwayat Presensi Pribadi (FR-14) - Link yang dicari Test tc11-13
        Route::get('/my-presensi', [PresensiController::class, 'myHistory']);
        
        // Riwayat Kedisiplinan Pribadi (FR-15)
        Route::get('/my-discipline', [PenilaianController::class, 'myHistory']);
    });
});