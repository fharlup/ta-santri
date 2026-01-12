<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\MasterTambahanController;

/* --- 1. GUEST --- */
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/* --- 2. AUTHENTICATED (SEMUA ROLE) --- */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DataMasterController::class, 'dashboard'])->name('kesiswaan.dashboard');

    /* --- 3. AKSES OPERASIONAL (Kesiswaan, Komdis, Wali Kelas) --- */
    Route::middleware('role:Kesiswaan,Komdis,Wali Kelas')->group(function () {
        
        // PRESENSI (SUDAH DITAMBAHKAN REKAP)
        Route::prefix('presensi')->name('presensi.')->group(function () {
            Route::get('/scan', [PresensiController::class, 'scanPage'])->name('scan');
            Route::post('/check', [PresensiController::class, 'checkRfid'])->name('check');
            Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');
            Route::get('/rekap', [KesiswaanController::class, 'rekapPresensi'])->name('rekap'); // <--- INI PENYEBAB ERROR TADI
            Route::get('/{id}/edit', [PresensiController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [PresensiController::class, 'update'])->name('update');
        });

        // PENILAIAN
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            Route::get('/create', [PenilaianController::class, 'create'])->name('create');
            Route::post('/store', [PenilaianController::class, 'store'])->name('store');
            Route::get('/rekap', [PenilaianController::class, 'rekap'])->name('rekap');
            Route::get('/{id}/edit', [PenilaianController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [PenilaianController::class, 'update'])->name('update');
        });
    });

    /* --- 4. AKSES ADMIN / KESISWAAN SAJA --- */
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {

        // MANAJEMEN SANTRIWATI
        Route::prefix('santri')->name('santri.')->group(function () {
            Route::get('/', [DataMasterController::class, 'index'])->name('index');
            Route::get('/tambah', [DataMasterController::class, 'create'])->name('create');
            Route::post('/simpan', [DataMasterController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DataMasterController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [DataMasterController::class, 'update'])->name('update');
            Route::delete('/{id}/hapus', [DataMasterController::class, 'destroy'])->name('destroy');
        });

        Route::resource('user', UserController::class);
        Route::resource('kegiatan', KegiatanController::class);

        // MASTER DATA (ANGKATAN & KELAS)
        Route::prefix('master-data')->name('master.')->group(function () {
            Route::get('/', [MasterTambahanController::class, 'index'])->name('index');
            Route::post('/angkatan', [MasterTambahanController::class, 'storeAngkatan'])->name('angkatan.store');
            Route::post('/kelas', [MasterTambahanController::class, 'storeKelas'])->name('kelas.store');
            Route::delete('/angkatan/{id}', [MasterTambahanController::class, 'destroyAngkatan'])->name('angkatan.destroy');
            Route::delete('/kelas/{id}', [MasterTambahanController::class, 'destroyKelas'])->name('kelas.destroy');
        });

        // EXPORT KHUSUS ADMIN
        Route::get('/presensi/export', [KesiswaanController::class, 'exportPresensi'])->name('presensi.export');
        Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
        Route::delete('/penilaian/{id}/hapus', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');
    });
});