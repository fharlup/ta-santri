<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\Kegiatan;    
use App\Exports\PenilaianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Presensi;
use Carbon\Carbon;
class PenilaianController extends Controller
{
    public function create()
    {
        $santris = Santriwati::all();
        return view('kesiswaan.penilaian.create', compact('santris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santriwati_id' => 'required',
            'tanggal' => 'required|date',
            'angkatan' => 'required',
            'disiplin' => 'required',
            'k3' => 'required',
            'tanggung_jawab' => 'required',
            'inisiatif_kreativitas' => 'required',
            'adab' => 'required',
            'berterate' => 'required',
            'kesabaran' => 'required',
            'produktif' => 'required',
            'mandiri' => 'required',
            'optimis' => 'required',
            'kejujuran' => 'required',
            'deskripsi' => 'nullable'
        ]);

        Penilaian::create($validated);

        return redirect()->route('penilaian.riwayat')->with('success', 'Data berhasil disubmit'); //
    }

    public function riwayat()
    {
        $penilaians = Penilaian::with('santriwati')->latest()->get(); //
        return view('kesiswaan.penilaian.riwayat', compact('penilaians'));
    }
    public function edit($id)
{
    $penilaian = Penilaian::with('santriwati')->findOrFail($id);
    $santris = Santriwati::all();
    return view('kesiswaan.penilaian.edit', compact('penilaian', 'santris'));
}

public function update(Request $request, $id)
{
    $penilaian = Penilaian::findOrFail($id);
    
    $validated = $request->validate([
        'santriwati_id' => 'required',
        'tanggal' => 'required|date',
        'angkatan' => 'required',
        'disiplin' => 'required',
        'k3' => 'required',
        'tanggung_jawab' => 'required',
        'inisiatif_kreativitas' => 'required',
        'adab' => 'required',
        'berterate' => 'required',
        'kesabaran' => 'required',
        'produktif' => 'required',
        'mandiri' => 'required',
        'optimis' => 'required',
        'kejujuran' => 'required',
        'deskripsi' => 'nullable'
    ]);

    $penilaian->update($validated);

    return redirect()->route('penilaian.riwayat')->with('success', 'Data penilaian berhasil diperbarui');
}
public function rekap(Request $request)
{
    // 1. Tangkap filter dari URL
    $angkatan = $request->get('angkatan');
    $tanggal  = $request->get('tanggal');

    // 2. Query data dengan filter
    $penilaians = Penilaian::with('santriwati')
        ->when($angkatan, function($q) use ($angkatan) {
            return $q->where('angkatan', $angkatan);
        })
        ->when($tanggal, function($q) use ($tanggal) {
            return $q->whereDate('tanggal', $tanggal);
        })
        ->latest()
        ->get();

    // 3. Ambil daftar angkatan untuk isi dropdown
    $allAngkatan = Santriwati::distinct()->pluck('angkatan');

    return view('kesiswaan.penilaian.rekap', compact('penilaians', 'allAngkatan'));
}
public function export(Request $request)
{
    $angkatan = $request->get('angkatan');
    $data = Penilaian::with('santriwati')
        ->when($angkatan, function($q) use ($angkatan) {
            return $q->where('angkatan', $angkatan);
        })->latest()->get();

    return Excel::download(new PenilaianExport($data), 'Rekap-Penilaian-Karakter.xlsx');
}

}
