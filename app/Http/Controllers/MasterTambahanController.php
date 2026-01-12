<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MasterTambahanController extends Controller
{
    /**
     * Menampilkan daftar Angkatan & Kelas dengan fitur Search
     */
    public function index(Request $request)
    {
        // 1. Logika Cari Angkatan
        $queryAngkatan = Angkatan::query();
        if ($request->filled('search_angkatan')) {
            $queryAngkatan->where('nama_angkatan', 'like', '%' . $request->search_angkatan . '%');
        }
        $angkatans = $queryAngkatan->orderBy('nama_angkatan', 'asc')->get();

        // 2. Logika Cari Kelas
        $queryKelas = Kelas::query();
        if ($request->filled('search_kelas')) {
            $queryKelas->where('nama_kelas', 'like', '%' . $request->search_kelas . '%');
        }
        $kelass = $queryKelas->orderBy('nama_kelas', 'asc')->get();

        // 3. Return ke View (sesuai folder kesiswaan.master_tambahan)
        return view('kesiswaan.master_tambahan.index', compact('angkatans', 'kelass'));
    }

    /**
     * Menyimpan data Angkatan baru
     */
    public function storeAngkatan(Request $request)
    {
        $request->validate([
            'nama_angkatan' => 'required|unique:angkatans,nama_angkatan',
        ]);

        Angkatan::create([
            'nama_angkatan' => $request->nama_angkatan
        ]);

        return redirect()->back()->with('success', 'Angkatan berhasil ditambahkan!');
    }

    /**
     * Menyimpan data Kelas baru
     */
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Menghapus data Angkatan
     */
    public function destroyAngkatan($id)
    {
        $angkatan = Angkatan::findOrFail($id);
        $angkatan->delete();

        return redirect()->back()->with('success', 'Angkatan telah dihapus!');
    }

    /**
     * Menghapus data Kelas
     */
    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas telah dihapus!');
    }
}