<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DataMasterController,
    KegiatanController,
    UserController,
    KesiswaanController,
    PenilaianController,
    PresensiController,
    RekapKegiatanController,
    MasterTambahanController
};

/* --- 1. GUEST (LOGIN) --- */
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

/* --- 2. AUTHENTICATED (WAJIB LOGIN) --- */
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Utama (Home)
    Route::get('/dashboard', [DataMasterController::class, 'dashboard'])
        ->name('kesiswaan.dashboard');

    /* |--------------------------------------------------------------------------
    | 3. PRESENSI (Kesiswaan, Komdis, Wali Kelas, Musyrifah)
    |---------------------------------------g-----------------------------------
    */
    Route::middleware('role:Kesiswaan,Komdis,Wali Kelas,Musyrifah')->prefix('presensi')->name('presensi.')->group(function () {
        Route::get('/scan', [PresensiController::class, 'scanPage'])->name('scan');
        Route::post('/check', [PresensiController::class, 'checkRfid'])->name('check');
        Route::get('/riwayat', [PresensiController::class, 'riwayat'])->name('riwayat');
        Route::get('/rekap', [KesiswaanController::class, 'rekapPresensi'])->name('rekap');
        Route::get('/export', [KesiswaanController::class, 'exportPresensi'])->name('export');
        Route::get('/{id}/edit', [PresensiController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PresensiController::class, 'update'])->name('update');
    });

    /* |--------------------------------------------------------------------------
    | 4. PENILAIAN (Kesiswaan, Komdis, Wali Kelas, Musyrifah)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:Kesiswaan,Komdis,Wali Kelas,Musyrifah'])->prefix('penilaian')->name('penilaian.')->group(function () {
        Route::get('/create', [PenilaianController::class, 'create'])->name('create');
        Route::post('/store', [PenilaianController::class, 'store'])->name('store');
        Route::get('/rekap', [PenilaianController::class, 'rekap'])->name('rekap');
        Route::get('/export', [PenilaianController::class, 'export'])->name('export');
        Route::get('/riwayat', [PenilaianController::class, 'riwayat'])->name('riwayat');
        Route::get('/{id}/edit', [PenilaianController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PenilaianController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [PenilaianController::class, 'destroy'])->name('destroy');
    });

    /* |--------------------------------------------------------------------------
    | 5. MASTER TAMBAHAN (Kesiswaan, Komdis, Wali Kelas, Musyrifah)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Kesiswaan,Komdis,Wali Kelas,Musyrifah')->prefix('master-tambahan')->name('master_tambahan.')->group(function () {
        Route::get('/', [MasterTambahanController::class, 'index'])->name('index');
        Route::post('/angkatan', [MasterTambahanController::class, 'storeAngkatan'])->name('angkatan.store');
        Route::post('/kelas', [MasterTambahanController::class, 'storeKelas'])->name('kelas.store');
        Route::delete('/angkatan/{id}', [MasterTambahanController::class, 'destroyAngkatan'])->name('angkatan.destroy');
        Route::delete('/kelas/{id}', [MasterTambahanController::class, 'destroyKelas'])->name('kelas.destroy');
    });

    /* |--------------------------------------------------------------------------
    | 6. MANAJEMEN (Kesiswaan & Komdis)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Kesiswaan,Komdis')->prefix('kesiswaan')->group(function () {

        // Manajemen Santriwati
        Route::prefix('santri')->name('santri.')->group(function () {
            Route::get('/', [DataMasterController::class, 'index'])->name('index');
            Route::get('/tambah', [DataMasterController::class, 'create'])->name('create');
            Route::post('/simpan', [DataMasterController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DataMasterController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [DataMasterController::class, 'update'])->name('update');
            Route::delete('/{id}/hapus', [DataMasterController::class, 'destroy'])->name('destroy');
        });

        // Manajemen Kegiatan
        Route::resource('kegiatan', KegiatanController::class);
    });

    /* |--------------------------------------------------------------------------
    | 7. MANAJEMEN USER & MASTER DATA (KHUSUS Kesiswaan)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Kesiswaan')->prefix('kesiswaan')->group(function () {

        // Manajemen Pengguna (User)
        Route::resource('user', UserController::class);
        
        // --- MASTER DATA (Angkatan & Kelas) ---
        Route::prefix('master-data')->group(function () {
            Route::get('/', [MasterTambahanController::class, 'index'])->name('master.index');
            Route::post('/angkatan', [MasterTambahanController::class, 'storeAngkatan'])->name('master.angkatan.store');
            Route::post('/kelas', [MasterTambahanController::class, 'storeKelas'])->name('master.kelas.store');
            Route::delete('/angkatan/{id}', [MasterTambahanController::class, 'destroyAngkatan'])->name('master.angkatan.destroy');
            Route::delete('/kelas/{id}', [MasterTambahanController::class, 'destroyKelas'])->name('master.kelas.destroy');
        });
    });
    Route::middleware(['auth'])->prefix('rekap-kegiatan')->group(function () {
    // Page 1: Pilih Anak (Admin Only)
    Route::get('/', [RekapKegiatanController::class, 'index'])->name('rekap.index');

    // Page 2: Overview Per Tahun (Jan - Des)
    Route::get('/tahunan/{santri_id}', [RekapKegiatanController::class, 'tahunan'])->name('rekap.tahunan');

    // Page 3: Detail Per Bulan (Minggu 1 - 4/5)
    Route::get('/bulanan/{santri_id}/{bulan}', [RekapKegiatanController::class, 'bulanan'])->name('rekap.bulanan');

    // Page 4: Detail Per Minggu (Checklist Harian)
    Route::get('/mingguan/{santri_id}/{bulan}/{minggu}', [RekapKegiatanController::class, 'mingguan'])->name('rekap.mingguan');
});
Route::middleware(['auth'])->prefix('rekap-kegiatan')->group(function () {
    Route::get('/', [RekapKegiatanController::class, 'index'])->name('rekap.index');
    Route::get('/tahunan/{santri_id}', [RekapKegiatanController::class, 'tahunan'])->name('rekap.tahunan');
    Route::get('/bulanan/{santri_id}/{bulan}', [RekapKegiatanController::class, 'bulanan'])->name('rekap.bulanan');
    Route::get('/mingguan/{santri_id}/{bulan}/{minggu}', [RekapKegiatanController::class, 'mingguan'])->name('rekap.mingguan');
});
});