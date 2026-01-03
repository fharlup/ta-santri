<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    // FR-02 & FR-08: Melihat daftar kegiatan
    public function index()
    {
        $kegiatans = Kegiatan::latest()->get();
        return view('kegiatan.index', compact('kegiatans'));
    }

    // FR-02 & FR-08: Menyimpan kegiatan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'ustadzah_pendamping' => 'required',
            'waktu_mulai' => 'required|date',
        ]);

        $kegiatan = Kegiatan::create($request->all());

        // FR-06: Catat aktivitas ke Log
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Membuat kegiatan baru: ' . $kegiatan->nama_kegiatan,
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    // FR-02 & FR-08: Mengupdate data kegiatan
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->all());

        // FR-06: Catat aktivitas ke Log
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah data kegiatan: ' . $kegiatan->nama_kegiatan,
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui!');
    }

    // FR-02 & FR-08: Menghapus kegiatan
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $nama = $kegiatan->nama_kegiatan;
        $kegiatan->delete();

        // FR-06: Catat aktivitas ke Log
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus kegiatan: ' . $nama,
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus!');
    }
}