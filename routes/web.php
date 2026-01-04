<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresensiController; // 1. PASTIKAN INI SUDAH DIIMPORT

/*
|--------------------------------------------------------------------------
| SI-DISIPLIN Tunas Qur'an - Full Routes Configuration
|--------------------------------------------------------------------------
*/

// 1. Rute Guest (Halaman Login)
Route::middleware('guest')->group(function () {
    Route::get('/', function () { 
        return view('auth.login'); 
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// 2. Rute Terautentikasi (Wajib Login)
Route::middleware('auth')->group(function () {
    
    // Proses Keluar Sistem
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- GRUP KHUSUS KESISWAAN (ADMIN) ---
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {
        
        // A. DASHBOARD UTAMA
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('kesiswaan.dashboard');

        // B. MANAJEMEN SANTRIWATI (Daftar, Tambah, Edit, Hapus)
        Route::prefix('santri')->group(function () {
            Route::get('/', [DataMasterController::class, 'index'])->name('santri.index');
            Route::get('/tambah', [DataMasterController::class, 'create'])->name('santri.create');
            Route::post('/simpan', [DataMasterController::class, 'store'])->name('santri.store');
            Route::get('/{id}/edit', [DataMasterController::class, 'edit'])->name('santri.edit');
            Route::put('/{id}/update', [DataMasterController::class, 'update'])->name('santri.update');
            Route::delete('/{id}/hapus', [DataMasterController::class, 'destroy'])->name('santri.destroy');
        });

        // C. MANAJEMEN JADWAL KEGIATAN (Tahajud s/d Komdis)
        Route::resource('kegiatan', KegiatanController::class)->names([
            'index'   => 'kegiatan.index',
            'create'  => 'kegiatan.create',
            'store'   => 'kegiatan.store',
            'edit'    => 'kegiatan.edit',
            'update'  => 'kegiatan.update',
            'destroy' => 'kegiatan.destroy',
        ]);

        // D. MANAJEMEN PENGGUNA (STAFF/ADMIN)
        Route::resource('user', UserController::class)->names([
            'index'   => 'user.index',
            'create'  => 'user.create',
            'store'   => 'user.store',
            'edit'    => 'user.edit',
            'update'  => 'user.update',
            'destroy' => 'user.destroy',
        ]);

        // E. MANAJEMEN PRESENSI (SCAN RFID & RIWAYAT)
        Route::prefix('presensi')->group(function () {
            // Rute ini yang tadi hilang dan menyebabkan error
            Route::get('/scan', [PresensiController::class, 'scanPage'])->name('presensi.scan');
            Route::post('/check', [PresensiController::class, 'checkRfid'])->name('presensi.check');
            Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
            Route::get('/presensi/{id}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
            Route::get('/presensi/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
Route::put('/presensi/{id}/update', [PresensiController::class, 'update'])->name('presensi.update');
        });

        // F. PENILAIAN
        Route::get('/penilaian', function() { return "Halaman Penilaian Skor"; })->name('kesiswaan.penilaian');
    });
});