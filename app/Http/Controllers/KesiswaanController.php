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
        $angkatanTerpilih = $request->get('angkatan');
        $santris = Santriwati::when($angkatanTerpilih, function($q) use ($angkatanTerpilih) {
            return $q->where('angkatan', $angkatanTerpilih);
        })->get();

        $listKegiatan = ['TAHAJJUD', 'SHUBUH', 'PIKET', 'APEL', 'HL DHUHA/KULIAH', 'SHOLAT DZUHUR', 'HL DZUHUR/KULIAH', 'ASHAR', 'BA/BM', 'MAGHRIB', 'ISYA', 'GH/M', 'KOMDIS'];

        $rekapData = [];
        foreach ($santris as $santri) {
            $row = ['nama' => $santri->nama_lengkap, 'angkatan' => $santri->angkatan];
            foreach ($listKegiatan as $kegName) {
                $totalJadwal = Kegiatan::where('nama_kegiatan', $kegName)->count();
                $totalHadir = Presensi::where('santriwati_id', $santri->id)
                    ->where('status', 'HADIR')
                    ->whereHas('kegiatan', function($q) use ($kegName) {
                        $q->where('nama_kegiatan', $kegName);
                    })->count();
                $row[$kegName] = $totalJadwal > 0 ? round(($totalHadir / $totalJadwal) * 100) : 0;
            }
            $rekapData[] = $row;
        }

        $allAngkatan = Santriwati::distinct()->pluck('angkatan');
        return view('kesiswaan.presensi.rekap', compact('rekapData', 'listKegiatan', 'allAngkatan'));
    }

    /**
     * FUNGSI EXPORT YANG TADI HILANG (Penyebab Error)
     */
    public function exportPresensi(Request $request)
    {
        $angkatanTerpilih = $request->get('angkatan');
        $santris = Santriwati::when($angkatanTerpilih, function($q) use ($angkatanTerpilih) {
            return $q->where('angkatan', $angkatanTerpilih);
        })->get();

        $listKegiatan = ['TAHAJJUD', 'SHUBUH', 'PIKET', 'APEL', 'HL DHUHA/KULIAH', 'SHOLAT DZUHUR', 'HL DHUHA/KULIAH', 'ASHAR', 'BA/BM', 'MAGHRIB', 'ISYA', 'GH/M', 'KOMDIS'];

        $exportData = [];
        foreach ($santris as $santri) {
            $row = ['nama' => $santri->nama_lengkap, 'angkatan' => $santri->angkatan];
            foreach ($listKegiatan as $kegName) {
                $totalJadwal = Kegiatan::where('nama_kegiatan', $kegName)->count();
                $totalHadir = Presensi::where('santriwati_id', $santri->id)
                    ->where('status', 'HADIR')
                    ->whereHas('kegiatan', function($q) use ($kegName) {
                        $q->where('nama_kegiatan', $kegName);
                    })->count();
                $row[$kegName] = $totalJadwal > 0 ? round(($totalHadir / $totalJadwal) * 100) . '%' : '0%';
            }
            $exportData[] = $row;
        }

        return Excel::download(new PresensiExport($exportData), 'Rekap-Presensi-Tunas-Quran.xlsx');
    }
}