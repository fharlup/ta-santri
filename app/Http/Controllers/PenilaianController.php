<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Santriwati;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    // Melihat semua data penilaian (Kesiswaan/KOMDIS/Musyrifah)
    public function index()
    {
        $penilaians = Penilaian::with('santriwati')->latest()->get();
        return view('penilaian.index', compact('penilaians'));
    }

    // FR-12: Musyrifah/Admin membuat data penilaian kedisiplinan
    public function store(Request $request)
    {
        $request->validate([
            'santriwati_id' => 'required',
            'muatan_karakter' => 'required',
            'skor' => 'required|integer',
            'deskripsi' => 'required',
        ]);

        $penilaian = Penilaian::create($request->all());

        // FR-06: Catat ke Log
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => "Memberi skor {$request->skor} kepada Santri ID: {$request->santriwati_id}",
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }

    // FR-15: Riwayat Kedisiplinan Pribadi untuk Santriwati
    public function myHistory()
    {
        // Asumsi user Santriwati terhubung dengan data santri lewat field 'username' atau 'related_id'
        // Di sini kita ambil data berdasarkan santri_id yang login
        $santri = Santriwati::where('nama_lengkap', Auth::user()->username)->first(); 
        
        $history = Penilaian::where('santriwati_id', $santri->id)->latest()->get();
        return view('santri.discipline_history', compact('history'));
    }

    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return redirect()->back()->with('success', 'Data penilaian dihapus!');
    }
}