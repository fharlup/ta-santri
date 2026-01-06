<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PresensiController;

/*
|--------------------------------------------------------------------------
| SI-DISIPLIN Tunas Qur'an - Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 1. GUEST (LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | 3. ROLE: KESISWAAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {

        /* ===== DASHBOARD ===== */
        Route::get('/dashboard', [DataMasterController::class, 'dashboard'])
            ->name('kesiswaan.dashboard');

        /* ===== SANTRIWATI ===== */
        Route::prefix('santri')->group(function () {
            Route::get('/', [DataMasterController::class, 'index'])->name('santri.index');
            Route::get('/tambah', [DataMasterController::class, 'create'])->name('santri.create');
            Route::post('/simpan', [DataMasterController::class, 'store'])->name('santri.store');
            Route::get('/{id}/edit', [DataMasterController::class, 'edit'])->name('santri.edit');
            Route::put('/{id}/update', [DataMasterController::class, 'update'])->name('santri.update');
            Route::delete('/{id}/hapus', [DataMasterController::class, 'destroy'])->name('santri.destroy');
        });

        /* ===== KEGIATAN ===== */
        Route::resource('kegiatan', KegiatanController::class)->names([
            'index'   => 'kegiatan.index',
            'create'  => 'kegiatan.create',
            'store'   => 'kegiatan.store',
            'edit'    => 'kegiatan.edit',
            'update'  => 'kegiatan.update',
            'destroy' => 'kegiatan.destroy',
        ]);

        /* ===== USER ===== */
        Route::resource('user', UserController::class)->names([
            'index'   => 'user.index',
            'create'  => 'user.create',
            'store'   => 'user.store',
            'edit'    => 'user.edit',
            'update'  => 'user.update',
            'destroy' => 'user.destroy',
        ]);

        /* ===== PRESENSI ===== */
        Route::prefix('presensi')->group(function () {
            Route::get('/scan', [PresensiController::class, 'scanPage'])
                ->name('presensi.scan');

            Route::post('/check', [PresensiController::class, 'checkRfid'])
                ->name('presensi.check');

            Route::get('/riwayat', [PresensiController::class, 'riwayat'])
                ->name('presensi.riwayat');

            Route::get('/{id}/edit', [PresensiController::class, 'edit'])
                ->name('presensi.edit');

            Route::put('/{id}/update', [PresensiController::class, 'update'])
                ->name('presensi.update');

            /* REKAP & EXPORT (KHUSUS KESISWAAN) */
            Route::get('/rekap', [KesiswaanController::class, 'rekapPresensi'])
                ->name('presensi.rekap');

            Route::get('/export', [KesiswaanController::class, 'exportPresensi'])
                ->name('presensi.export');
        });

        /* ===== PENILAIAN ===== */
        Route::prefix('penilaian')->group(function () {
            Route::get('/export', [PenilaianController::class, 'export'])->name('penilaian.export');
            Route::get('/rekap', [PenilaianController::class, 'rekap'])
                ->name('penilaian.rekap');

            Route::get('/riwayat', [PenilaianController::class, 'riwayat'])
                ->name('penilaian.riwayat');

            Route::get('/create', [PenilaianController::class, 'create'])
                ->name('penilaian.create');

            Route::post('/store', [PenilaianController::class, 'store'])
                ->name('penilaian.store');

            Route::get('/{id}/edit', [PenilaianController::class, 'edit'])
                ->name('penilaian.edit');

            Route::put('/{id}/update', [PenilaianController::class, 'update'])
                ->name('penilaian.update');

            Route::delete('/{id}/destroy', [PenilaianController::class, 'destroy'])
                ->name('penilaian.destroy');
        });

    });
});
