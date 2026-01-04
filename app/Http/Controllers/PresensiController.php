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
    $kegiatan = Kegiatan::whereTime('jam', '>=', $now->copy()->subMinutes(45)->format('H:i:s'))
                        ->whereTime('jam', '<=', $now->copy()->addMinutes(60)->format('H:i:s'))
                        ->first();

    if (!$kegiatan) return response()->json(['success' => false, 'message' => 'Tidak ada jadwal!']);

    $jadwal = Carbon::parse($kegiatan->jam);
    $status = $now->gt($jadwal->addMinutes(15)) ? 'Terlambat' : 'Tepat Waktu';

    // JIKA TERLAMBAT: Pastikan NIM dan KELAS tetap dikirim ke FE
    if ($status === 'Terlambat' && !$request->filled('keterangan')) {
        return response()->json([
            'success' => true,
            'require_keterangan' => true,
            'nama' => $santri->nama_lengkap,
            'nim' => $santri->nim,   // Tambahkan ini agar tidak undefined
            'kelas' => $santri->kelas, // Tambahkan ini agar tidak undefined
            'status' => 'Terlambat'
        ]);
    }

    // SIMPAN DATA
    Presensi::create([
        'santriwati_id' => $santri->id,
        'kegiatan_id' => $kegiatan->id,
        'waktu_scan' => $now,
        'status' => $status,
        'keterangan' => $request->keterangan
    ]);

    return response()->json([
        'success' => true,
        'require_keterangan' => false,
        'nama' => $santri->nama_lengkap,
        'nim' => $santri->nim,   // Pastikan NIM dikirim
        'kelas' => $santri->kelas, // Pastikan KELAS dikirim
        'kegiatan' => $kegiatan->nama_kegiatan,
        'waktu' => $now->format('H:i:s'),
        'status' => $status
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
}