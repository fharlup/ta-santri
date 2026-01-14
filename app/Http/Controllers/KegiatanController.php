<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    // FR-02 & FR-08: Melihat daftar kegiatan
    public function create() {
        return view('kesiswaan.kegiatan.create');
    }

   public function store(Request $request)
{
    // 1. Validasi: Tanggal sekarang bersifat nullable (opsional)
    $request->validate([
        'nama_kegiatan' => 'required|string',
        'jam'           => 'required',
        'tanggal'       => 'nullable|date',
        'ustadzah_1'    => 'nullable|string',
        'ustadzah_2'    => 'nullable|string',
        'ustadzah_3'    => 'nullable|string',
    ]);

    try {
        // 2. Simpan ke database
        Kegiatan::create([
            'nama_kegiatan' => strtoupper($request->nama_kegiatan),
            'jam'           => $request->jam,
            // Jika tanggal kosong, otomatis isi dengan tanggal hari ini
            'tanggal'       => $request->tanggal ?? now()->format('Y-m-d'), 
            // Angkatan dihapus dari form, jadi kita beri default "Semua"
            'angkatan'      => 'Semua', 
            'ustadzah_1'    => $request->ustadzah_1,
            'ustadzah_2'    => $request->ustadzah_2,
            'ustadzah_3'    => $request->ustadzah_3,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil disimpan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal simpan: ' . $e->getMessage());
    }
}

    public function edit($id) {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kesiswaan.kegiatan.edit', compact('kegiatan'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'nama_kegiatan' => 'required|string',
        'jam'           => 'required',
        'tanggal'       => 'nullable|date',
        'ustadzah_1'    => 'nullable|string',
        'ustadzah_2'    => 'nullable|string',
        'ustadzah_3'    => 'nullable|string',
    ]);

    $kegiatan = Kegiatan::findOrFail($id);

    try {
        $kegiatan->update([
            'nama_kegiatan' => strtoupper($request->nama_kegiatan),
            'jam'           => $request->jam,
            'tanggal'       => $request->tanggal ?? $kegiatan->tanggal ?? now()->format('Y-m-d'),
            'angkatan'      => 'Semua', // Tetap set "Semua" agar konsisten
            'ustadzah_1'    => $request->ustadzah_1,
            'ustadzah_2'    => $request->ustadzah_2,
            'ustadzah_3'    => $request->ustadzah_3,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Jadwal berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
    }
}
    public function destroy($id) {
        Kegiatan::findOrFail($id)->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan dihapus');
    } 
public function index(Request $request)
{
    // 1. Inisialisasi query
    $query = \App\Models\Kegiatan::query();

    // 2. Logika Search Nama Kegiatan
    if ($request->filled('search')) {
        $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
    }

    // 3. Ambil data dengan urutan jam terkecil (paling awal)
    $kegiatans = $query->orderBy('jam', 'asc')->get();

    return view('kesiswaan.kegiatan.index', compact('kegiatans'));
}
}