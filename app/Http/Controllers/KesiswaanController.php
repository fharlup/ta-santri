<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use App\Models\Penilaian;
use App\Exports\PresensiExport; // WAJIB ADA
use Maatwebsite\Excel\Facades\Excel; // WAJIB ADA

class KesiswaanController extends Controller
{
    // Fungsi Rekap yang sudah ada...
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
    /**
     * FUNGSI EXPORT YANG TADI HILANG (Penyebab Error)
     */
    public function exportPresensi(Request $request)
{
    $angkatanTerpilih = $request->get('angkatan');
    $tanggalFilter = $request->get('tanggal') ?? now()->format('Y-m-d');

    // 1. Ambil Nama Kegiatan secara Dinamis dari Database
    $listKegiatan = \App\Models\Kegiatan::distinct()->pluck('nama_kegiatan')->toArray();

    $santris = Santriwati::when($angkatanTerpilih, function($q) use ($angkatanTerpilih) {
        return $q->where('angkatan', $angkatanTerpilih);
    })->get();

    $exportData = [];
    foreach ($santris as $santri) {
        $row = ['nama' => $santri->nama_lengkap, 'angkatan' => $santri->angkatan];
        
        foreach ($listKegiatan as $kegName) {
            // Hitung jadwal pada tanggal filter
            $totalJadwal = Kegiatan::where('nama_kegiatan', $kegName)
                                    ->whereDate('tanggal', $tanggalFilter)
                                    ->count();
            
            // Hitung kehadiran santri pada tanggal filter
            $totalHadir = Presensi::where('santriwati_id', $santri->id)
                ->whereIn('status', ['HADIR', 'TELAT'])
                ->whereDate('waktu_scan', $tanggalFilter)
                ->whereHas('kegiatan', function($q) use ($kegName) {
                    $q->where('nama_kegiatan', $kegName);
                })->count();

            // Kirim angka murni (tanpa %)
            $row[$kegName] = $totalJadwal > 0 ? round(($totalHadir / $totalJadwal) * 100) : 0;
        }
        $exportData[] = $row;
    }

    // Sertakan $listKegiatan saat memanggil constructor Exporter
    return Excel::download(new PresensiExport($exportData, $listKegiatan), "Rekap-Presensi-$tanggalFilter.xlsx");
}
}