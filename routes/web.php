
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

/*
|--------------------------------------------------------------------------
| SI-DISIPLIN Tunas Qur'an - Routes Full Configuration
|--------------------------------------------------------------------------
*/

/* --- 1. GUEST (LOGIN) --- */
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

/* --- 2. AUTHENTICATED (SEMUA ROLE YANG SUDAH LOGIN) --- */
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /**
     * DASHBOARD UTAMA
     * Dapat diakses oleh semua role (Kesiswaan, Komdis, Wali Kelas, Santri).
     * Logika tampilan dibedakan di dalam file Dashboard Blade menggunakan @if.
     */
    Route::get('/dashboard', [DataMasterController::class, 'dashboard'])
        ->name('kesiswaan.dashboard');

    /*
    |--------------------------------------------------------------------------
    | 3. AKSES OPERASIONAL (Kesiswaan, Komdis, Wali Kelas)
    |--------------------------------------------------------------------------
    | Fitur harian untuk input nilai dan scan presensi.
    */
    Route::middleware('role:Kesiswaan,Komdis,Wali Kelas')->group(function () {
        
        // PRESENSI (Harian)
        Route::prefix('presensi')->name('presensi.')->group(function () {
            Route::get('/scan', [PresensiController::class, 'scanPage'])->name('scan');
            Route::post('/check', [PresensiController::class, 'checkRfid'])->name('check');
            Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');
            Route::get('/{id}/edit', [PresensiController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [PresensiController::class, 'update'])->name('update');
            Route::get('/rekap', [KesiswaanController::class, 'rekapPresensi'])->name('rekap');
        });

        // PENILAIAN AHLAK
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            Route::get('/create', [PenilaianController::class, 'create'])->name('create');
            Route::post('/store', [PenilaianController::class, 'store'])->name('store');
            Route::get('/rekap', [PenilaianController::class, 'rekap'])->name('rekap');
            Route::get('/{id}/edit', [PenilaianController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [PenilaianController::class, 'update'])->name('update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 4. AKSES ADMIN (Hanya Role Kesiswaan)
    |--------------------------------------------------------------------------
    | Fitur manajemen data master, hapus data, dan export laporan.
    */
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

        // MANAJEMEN KEGIATAN & PENGGUNA (Resource)
        Route::resource('kegiatan', KegiatanController::class);
        Route::resource('user', UserController::class); // Menggunakan 'user' tunggal sesuai folder Anda

        // MASTER DATA (Angkatan & Kelas)
        Route::prefix('master-data')->name('master.')->group(function () {
            Route::get('/', [MasterTambahanController::class, 'index'])->name('tambahan');
            Route::post('/angkatan', [MasterTambahanController::class, 'storeAngkatan'])->name('angkatan.store');
            Route::post('/kelas', [MasterTambahanController::class, 'storeKelas'])->name('kelas.store');
            Route::delete('/angkatan/{id}', [MasterTambahanController::class, 'destroyAngkatan'])->name('angkatan.destroy');
            Route::delete('/kelas/{id}', [MasterTambahanController::class, 'destroyKelas'])->name('kelas.destroy');
        });

        // FITUR EXPORT & HAPUS NILAI
        Route::get('/presensi/export', [KesiswaanController::class, 'exportPresensi'])->name('presensi.export');
        Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
        Route::delete('/penilaian/{id}/hapus', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');
    });

});