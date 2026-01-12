<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * SCAN PAGE: Halaman input RFID (Solusi Error $kegiatanAktif)
     */
    public function scanPage()
    {
        $now = Carbon::now()->format('H:i:s');

        // Mencari kegiatan yang jam-nya paling mendekati waktu sekarang
        // atau Anda bisa menyesuaikan logikanya (misal: kegiatan hari ini)
        $kegiatanAktif = Kegiatan::where('jam', '<=', $now)
                            ->orderBy('jam', 'desc')
                            ->first();

        // Jika tidak ada kegiatan yang pas, ambil kegiatan pertama sebagai default
        if (!$kegiatanAktif) {
            $kegiatanAktif = Kegiatan::orderBy('jam', 'asc')->first();
        }

        $kegiatans = Kegiatan::all();

        // Pastikan $kegiatanAktif dikirim ke view agar tidak error
        return view('kesiswaan.presensi.scan', compact('kegiatans', 'kegiatanAktif'));
    }

    /**
     * CHECK RFID: Proses validasi kartu saat di-tap
     */
    public function checkRfid(Request $request)
    {
        $request->validate([
            'rfid' => 'required', // Nama input di blade Anda adalah 'rfid'
            'kegiatan_id' => 'required'
        ]);

        $santri = Santriwati::where('rfid_id', $request->rfid)->first();

        if (!$santri) {
            return back()->with('error', 'Kartu RFID tidak terdaftar!');
        }

        // Cek duplikasi presensi hari ini untuk kegiatan yang sama
        $sudahAbsen = Presensi::where('santriwati_id', $santri->id)
                                ->where('kegiatan_id', $request->kegiatan_id)
                                ->whereDate('waktu_scan', Carbon::today())
                                ->exists();

        if ($sudahAbsen) {
            return back()->with('info', $santri->nama_lengkap . ' sudah absen.');
        }

        Presensi::create([
            'santriwati_id' => $santri->id,
            'kegiatan_id' => $request->kegiatan_id,
            'waktu_scan' => now(),
            'status' => 'Hadir'
        ]);

        return back()->with('success', 'Berhasil! ' . $santri->nama_lengkap . ' hadir.');
    }

    /**
     * RIWAYAT: Menampilkan data kehadiran dengan filter
     */
    public function riwayat(Request $request)
    {
        $allAngkatan = Santriwati::distinct()->whereNotNull('angkatan')->pluck('angkatan');
        $query = Presensi::with(['santriwati', 'kegiatan']);

        if ($request->filled('search')) {
            $query->whereHas('santriwati', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('angkatan')) {
            $query->whereHas('santriwati', function($q) use ($request) {
                $q->where('angkatan', $request->angkatan);
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_scan', $request->tanggal);
        } else {
            $query->whereDate('waktu_scan', Carbon::today());
        }

        $presensis = $query->latest('waktu_scan')->paginate(20);

        return view('kesiswaan.presensi.riwayat', compact('presensis', 'allAngkatan'));
    }

    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        $kegiatans = Kegiatan::all();
        return view('kesiswaan.presensi.edit', compact('presensi', 'kegiatans'));
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update($request->all());
        return redirect()->route('presensi.riwayat')->with('success', 'Data diperbarui.');
    }
}