<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
public function checkRfid(Request $request)
{
    $waktuSekarang = now();
    $jamSekarang = $waktuSekarang->format('H:i:s');
    $hariIni = $waktuSekarang->toDateString();

    // 1. Cari Kegiatan Aktif
    $kegiatan = Kegiatan::whereDate('tanggal', $hariIni)
                        ->whereRaw('jam <= ?', [$jamSekarang])
                        ->whereRaw('ADDTIME(jam, "00:45:00") >= ?', [$jamSekarang])
                        ->first();

    if (!$kegiatan) {
        return redirect()->back()->with('error', 'TIDAK ADA JADWAL AKTIF.');
    }

    // 2. Cari Data Santri
    $santri = Santriwati::where('rfid_id', $request->rfid)->first();
    if (!$santri) {
        return redirect()->back()->with('error', 'KARTU TIDAK TERDAFTAR!');
    }

    // 3. LOGIKA CEK DOUBLE SCAN (Penting)
    // Mencari apakah sudah ada data presensi untuk santri ini di kegiatan ini pada hari ini
    $sudahAbsen = Presensi::where('santriwati_id', $santri->id)
                        ->where('kegiatan_id', $kegiatan->id)
                        ->whereDate('waktu_scan', $hariIni)
                        ->first();

    if ($sudahAbsen) {
        // Jika sudah ada, kembalikan pesan informasi dan tampilkan data santri tersebut
        return redirect()->back()->with([
            'info' => "SANTRI SUDAH SCAN SEBELUMNYA!",
            'last_santri' => $santri,
            'status_absen' => $sudahAbsen->status // Menampilkan status (HADIR/TELAT) yang sudah tercatat
        ]);
    }

    // 4. Logika Status Telat (Toleransi 10 Menit)
    $batasHadir = \Carbon\Carbon::parse($kegiatan->jam)->addMinutes(10);
    $status = $waktuSekarang->greaterThan($batasHadir) ? 'TELAT' : 'HADIR';

    // 5. Simpan Data Baru jika belum ada
    Presensi::create([
        'santriwati_id' => $santri->id,
        'kegiatan_id'   => $kegiatan->id,
        'waktu_scan'    => $waktuSekarang,
        'status'        => $status,
    ]);

    return redirect()->back()->with([
        'success' => "BERHASIL ABSEN: $status",
        'last_santri' => $santri,
        'status_absen' => $status
    ]);
}
public function riwayat(Request $request)
{
    $query = Presensi::with(['santriwati', 'kegiatan']);

    // Filter berdasarkan Pencarian Nama
    if ($request->has('search')) {
        $query->whereHas('santriwati', function($q) use ($request) {
            $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
        });
    }

    // Filter berdasarkan Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $presensis = $query->latest()->get();

    return view('kesiswaan.presensi.riwayat', compact('presensis'));
}
public function edit($id)
{
    $presensi = Presensi::with(['santriwati', 'kegiatan'])->findOrFail($id);
    $kegiatans = Kegiatan::all(); // Untuk dropdown Nama Kegiatan
    
    return view('kesiswaan.presensi.edit', compact('presensi', 'kegiatans'));
}

// Memproses pembaruan data
public function update(Request $request, $id)
{
    // 1. Cari data presensi
    $presensi = Presensi::findOrFail($id);

    // 2. Validasi input
    $request->validate([
        'status' => 'required',
        'kegiatan_id' => 'required|exists:kegiatans,id',
        'keterangan' => 'nullable|string'
    ]);

    // 3. Update data secara manual untuk memastikan status berubah
    $presensi->status = $request->status;
    $presensi->kegiatan_id = $request->kegiatan_id;
    $presensi->keterangan = $request->keterangan;
    
    // Simpan perubahan
    $presensi->save();

    // 4. Redirect kembali ke Riwayat dengan pesan sukses
    return redirect()->route('presensi.riwayat')->with('success', 'Presensi ' . $presensi->santriwati->nama_lengkap . ' berhasil diupdate');
}
public function rekap()
{
    // Ini adalah contoh logika pengambilan data. 
    // Anda bisa menyesuaikan query ini dengan struktur tabel Anda.
    // Gunakan 'santriwatis' dan 'presensis' sesuai foto database
  $data_rekap = \DB::table('santriwatis')
        ->leftJoin('presensis', 'santriwatis.id', '=', 'presensis.santriwati_id')
        ->select(
            // Ganti 'nama' menjadi 'nama_lengkap' sesuai struktur tabel Anda
            'santriwatis.nama_lengkap as nama_santri', 
            \DB::raw('SUM(CASE WHEN presensis.status = "hadir" THEN 1 ELSE 0 END) as hadir'),
            \DB::raw('SUM(CASE WHEN presensis.status = "izin" THEN 1 ELSE 0 END) as izin'),
            \DB::raw('SUM(CASE WHEN presensis.status = "sakit" THEN 1 ELSE 0 END) as sakit'),
            \DB::raw('SUM(CASE WHEN presensis.status = "alpa" THEN 1 ELSE 0 END) as alpa')
        )
        // Pastikan di groupBy juga menggunakan 'nama_lengkap'
        ->groupBy('santriwatis.id', 'santriwatis.nama_lengkap') 
        ->get();

    return view('kesiswaan.presensi.rekap', compact('data_rekap'));
}
public function rekapPresensi(Request $request)
{
    // 1. Ambil input filter
    $angkatanFilter = $request->get('angkatan');
    $tanggalFilter = $request->get('tanggal') ?? now()->format('Y-m-d'); // Default hari ini

    // 2. Ambil daftar angkatan untuk dropdown
    $allAngkatan = \App\Models\Angkatan::all();

    // 3. Ambil daftar kegiatan (13 kegiatan utama)
    $listKegiatan = \App\Models\Kegiatan::distinct()->pluck('nama_kegiatan');

    // 4. Ambil data santri dengan filter angkatan
    $santris = \App\Models\Santriwati::when($angkatanFilter, function($q) use ($angkatanFilter) {
            return $q->where('angkatan', $angkatanFilter);
        })->get();

    $rekapData = [];

    foreach ($santris as $s) {
        $row = [
            'nama' => $s->nama_lengkap,
            'angkatan' => $s->angkatan,
        ];

        foreach ($listKegiatan as $keg) {
            // Hitung kehadiran per kegiatan pada tanggal yang dipilih
            $present = \App\Models\Presensi::where('santriwati_id', $s->id)
                ->whereHas('kegiatan', function($q) use ($keg, $tanggalFilter) {
                    $q->where('nama_kegiatan', $keg)
                      ->whereDate('tanggal', $tanggalFilter);
                })
                ->whereIn('status', ['HADIR', 'TELAT'])
                ->exists();

            $row[$keg] = $present ? 100 : 0;
        }
        $rekapData[] = $row;
    }

    return view('kesiswaan.presensi.rekap', compact('rekapData', 'listKegiatan', 'allAngkatan'));
}
public function scanPage()
{
    $waktuSekarang = now();
    $jamSekarang = $waktuSekarang->format('H:i:s');
    $hariIni = $waktuSekarang->toDateString();

    // Logika pencarian kegiatan aktif (Sama dengan fungsi checkRfid)
    $kegiatanAktif = \App\Models\Kegiatan::whereDate('tanggal', $hariIni)
                        ->whereRaw('jam <= ?', [$jamSekarang])
                        ->whereRaw('ADDTIME(jam, "00:45:00") >= ?', [$jamSekarang])
                        ->first();

    return view('kesiswaan.presensi.scan', compact('kegiatanAktif'));
}
}