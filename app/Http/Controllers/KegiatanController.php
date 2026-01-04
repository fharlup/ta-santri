<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    // FR-02 & FR-08: Melihat daftar kegiatan
   public function index() {
        $kegiatans = Kegiatan::orderBy('jam', 'asc')->get();
        return view('kesiswaan.kegiatan.index', compact('kegiatans'));
    }

    public function create() {
        return view('kesiswaan.kegiatan.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jam' => 'required'
        ]);

        Kegiatan::create($request->all());
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambah');
    }

    public function edit($id) {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kesiswaan.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id) {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->all());
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate');
    }
    public function destroy($id) {
        Kegiatan::findOrFail($id)->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan dihapus');
    } 
}