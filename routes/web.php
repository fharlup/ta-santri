<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\KegiatanController;

/*
|--------------------------------------------------------------------------
| SI-DISIPLIN Tunas Qur'an Web Routes
|--------------------------------------------------------------------------
*/

// 1. GUEST: Rute sebelum login
Route::middleware('guest')->group(function () {
    // Halaman Login utama
    Route::get('/', function () { 
        return view('auth.login'); 
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// 2. AUTH: Rute setelah login (Wajib Masuk)
Route::middleware('auth')->group(function () {
    
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- KELOMPOK: KESISWAAN (Admin Utama) ---
    // Diproteksi oleh Middleware Role dan Prefix URL /kesiswaan/
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {
        
        // A. DASHBOARD RINGKASAN
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

        // C. MANAJEMEN KEGIATAN (Sholat Dzuhur, dll)
        // Menggunakan Resource agar lebih ringkas (Index, Create, Store, Edit, Update, Destroy)
        Route::resource('kegiatan', KegiatanController::class)->names([
            'index'   => 'kegiatan.index',
            'create'  => 'kegiatan.create',
            'store'   => 'kegiatan.store',
            'edit'    => 'kegiatan.edit',
            'update'  => 'kegiatan.update',
            'destroy' => 'kegiatan.destroy',
        ]);

        // D. MENU LAIN (Placeholder sesuai Sidebar)
        Route::get('/presensi', function() { return "Halaman Presensi"; })->name('kesiswaan.presensi');
        Route::get('/penilaian', function() { return "Halaman Penilaian"; })->name('kesiswaan.penilaian');
    });

});