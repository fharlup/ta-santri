<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataMasterController extends Controller
{
    // Fungsi untuk Dashboard (FR-02)
    public function dashboard()
    {
        return view('dashboard'); // Pastikan file view ini ada nanti
    }

    // WAJIB bernama 'store' agar cocok dengan Route::resource
    public function store(Request $request)
    {
        // Validasi data (Penting agar data tidak kosong)
        $request->validate([
            'nim' => 'required|unique:santriwatis',
            'nama_lengkap' => 'required',
            'kelas' => 'required',
            'rfid_id' => 'required|unique:santriwatis',
        ]);

        $santri = Santriwati::create($request->all());

        // FR-06: Catat ke Log System
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambah data santri: ' . $santri->nama_lengkap,
        ]);

        return back()->with('success', 'Data berhasil disimpan');
    }

    // WAJIB bernama 'destroy' agar cocok dengan Route::resource
    public function destroy($id)
    {
        $santri = Santriwati::findOrFail($id);
        $nama = $santri->nama_lengkap;
        $santri->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus data santri: ' . $nama,
        ]);

        return back()->with('success', 'Data berhasil dihapus');
    }
}