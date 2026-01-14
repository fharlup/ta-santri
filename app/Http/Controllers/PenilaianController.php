<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Penilaian;
use App\Models\Angkatan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\PenilaianExport;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianController extends Controller
{
    public function create()
    {
        $santris = Santriwati::all();
        return view('kesiswaan.penilaian.create', compact('santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santriwati_id' => 'required|exists:santriwatis,id',
            'tanggal' => 'required|date',
        ]);

        $santri = Santriwati::findOrFail($request->santriwati_id);

        Penilaian::create([
            'santriwati_id'         => $santri->id,
            'user_id'               => auth()->id(),
            'tanggal'               => $request->tanggal,
            'angkatan'              => $santri->angkatan,
            'disiplin'              => $request->disiplin ?? 'B',
            'k3'                    => $request->k3 ?? 'B',
            'tanggung_jawab'        => $request->tanggung_jawab ?? 'B',
            'inisiatif_kreatifitas' => $request->inisiatif_kreatifitas ?? 'B',
            'adab'                  => $request->adab ?? 'B',
            'berterate'             => $request->berterate ?? 'B',
            'integritas_kesabaran'  => $request->integritas_kesabaran ?? 'B',
            'integritas_produktif'  => $request->integritas_produktif ?? 'B',
            'integritas_mandiri'    => $request->integritas_mandiri ?? 'B',
            'integritas_optimis'    => $request->integritas_optimis ?? 'B',
            'integritas_kejujuran'  => $request->integritas_kejujuran ?? 'B',
            'deskripsi'             => $request->deskripsi,
        ]);

        return redirect()->route('penilaian.rekap')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function rekap(Request $request)
    {
        // 1. Ambil list angkatan untuk filter
        $allAngkatan = Angkatan::all();
        
        // 2. Query data PENILAIAN (Bukan Presensi)
        $query = Penilaian::with('santriwati');

        // Filter Nama Santriwati
        if ($request->filled('search')) {
            $query->whereHas('santriwati', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $penilaians = $query->latest()->paginate(20);

        // 3. Kembalikan ke view PENILAIAN
        return view('kesiswaan.penilaian.rekap', compact('penilaians', 'allAngkatan'));
    }

    public function edit($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $santris = Santriwati::all();
        return view('kesiswaan.penilaian.edit', compact('penilaian', 'santris'));
    }

    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);
        
        // Sesuaikan nama field dengan store agar konsisten
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'disiplin' => 'required',
            'k3' => 'required',
            'tanggung_jawab' => 'required',
            'adab' => 'required',
            'deskripsi' => 'nullable'
        ]);

        $penilaian->update($request->all());

        return redirect()->route('penilaian.rekap')->with('success', 'Data penilaian berhasil diperbarui');
    }
}