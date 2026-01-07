<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Angkatan;
use App\Models\Kelas;

class MasterTambahanController extends Controller
{
    public function index() {
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();
        return view('kesiswaan.master_tambahan.index', compact('angkatans', 'kelas'));
    }

    public function storeAngkatan(Request $request) {
        $request->validate(['nama_angkatan' => 'required|unique:angkatans']);
        Angkatan::create($request->all());
        return back()->with('success', 'Angkatan berhasil ditambah!');
    }

    public function storeKelas(Request $request) {
        $request->validate(['nama_kelas' => 'required|unique:kelas']);
        Kelas::create($request->all());
        return back()->with('success', 'Kelas berhasil ditambah!');
    }

    public function destroyAngkatan($id) {
        Angkatan::destroy($id);
        return back()->with('success', 'Angkatan dihapus!');
    }

    public function destroyKelas($id) {
        Kelas::destroy($id);
        return back()->with('success', 'Kelas dihapus!');
    }
}