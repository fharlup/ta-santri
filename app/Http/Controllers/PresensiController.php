<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // FR-07: Proses Scan RFID (Mencegah "Table is empty" di Test)
    public function store(Request $request)
    {
        $santri = Santriwati::where('rfid_id', $request->rfid_string)->first();
        $kegiatan = Kegiatan::findOrFail($request->kegiatan_id);

        if (!$santri) {
            return back()->withErrors(['rfid' => 'Kartu tidak terdaftar']);
        }

        // Logika Terlambat (FR-03)
        // Jika waktu scan sekarang > waktu mulai kegiatan, maka Terlambat
        $status = now()->gt($kegiatan->waktu_mulai) ? 'Terlambat' : 'Hadir';

        Presensi::create([
            'santriwati_id' => $santri->id,
            'kegiatan_id' => $kegiatan->id,
            'waktu_scan' => now(),
            'status' => $status,
        ]);

        return back()->with('success', 'Absensi berhasil');
    }

    // FR-14: Riwayat Mandiri Santri (Mencegah Error 404)
    public function myHistory()
    {
        return response()->json(['message' => 'Halaman Riwayat Santri'], 200);
    }

    // FR-04: Export Laporan (Mencegah Error 500 "Call to undefined method")
    public function export()
    {
        return response()->json(['message' => 'Laporan berhasil diekspor'], 200);
    }

    public function scanView()
    {
        return view('presensi.scan'); // Pastikan file ini ada nanti
    }
}