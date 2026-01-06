<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function scanPage()
    {
        return view('kesiswaan.presensi.scan');
    }

public function checkRfid(Request $request)
    {
        $santri = Santriwati::where('rfid_id', $request->rfid_id)->first();
        if (!$santri) return response()->json(['success' => false, 'message' => 'Kartu Tidak Sah!']);

        $now = Carbon::now();
        // Mencari kegiatan berdasarkan waktu saat ini [cite: 16]
        $kegiatan = Kegiatan::whereDate('tanggal', Carbon::today())
                            ->whereTime('jam', '>=', $now->copy()->subMinutes(45)->format('H:i:s'))
                            ->whereTime('jam', '<=', $now->copy()->addMinutes(60)->format('H:i:s'))
                            ->first();

        if (!$kegiatan) return response()->json(['success' => false, 'message' => 'Tidak ada jadwal aktif!']);

        // LOGIKA BARU: Toleransi 10 Menit [cite: 12, 13]
        $waktuMulai = Carbon::parse($kegiatan->jam);
        $batasToleransi = $waktuMulai->copy()->addMinutes(10);
        
        // Status: HADIR atau TELAT [cite: 18]
        $status = $now->gt($batasToleransi) ? 'TELAT' : 'HADIR';

        // Minta keterangan jika TELAT 
        if ($status === 'TELAT' && !$request->filled('keterangan')) {
            return response()->json([
                'success' => true,
                'require_keterangan' => true,
                'nama' => $santri->nama_lengkap,
                'nim' => $santri->nim,
                'kelas' => $santri->kelas,
                'status' => 'TELAT'
            ]);
        }

        $presensi = Presensi::create([
            'santriwati_id' => $santri->id,
            'kegiatan_id' => $kegiatan->id,
            'waktu_scan' => $now,
            'status' => $status,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'success' => true,
            'nama' => $santri->nama_lengkap,
            'status' => $status,
            'kegiatan' => $kegiatan->nama_kegiatan,
            'waktu' => $now->format('H:i:s')
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
}