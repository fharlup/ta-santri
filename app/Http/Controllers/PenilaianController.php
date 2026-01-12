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
use App\Models\User;          // Tambahkan ini
class PenilaianController extends Controller
{
    public function create()
    {
        $santris = Santriwati::all();
        return view('kesiswaan.penilaian.create', compact('santris'));
    }

 
    
public function store(Request $request)
{
    // 1. Validasi dasar
    $request->validate([
        'santriwati_id' => 'required|exists:santriwatis,id',
        'tanggal' => 'required',
    ]);

    // 2. Ambil data santri secara otomatis untuk mendapatkan 'angkatan'
    $santri = \App\Models\Santriwati::findOrFail($request->santriwati_id);

    // 3. Simpan data ke database
    \App\Models\Penilaian::create([
        'santriwati_id'         => $santri->id,
        'user_id'               => auth()->id(),
        'tanggal'               => $request->tanggal,
        'angkatan'              => $santri->angkatan, // OTOMATIS mengambil dari data santri
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

public function export(Request $request)
{
    // 1. Ambil daftar User (Ustadzah) agar kolom otomatis bertambah
    $listUstadzah = User::whereIn('role', ['Kesiswaan', 'Komdis', 'Wali Kelas'])
                    ->get(['id', 'nama_lengkap']);

    // 2. Ambil data Santri dengan filter yang sama dengan Rekap (opsional)
    $querySantri = Santriwati::query();
    if ($request->filled('angkatan')) {
        $querySantri->where('angkatan', $request->angkatan);
    }
    $santris = $querySantri->get();

    $exportData = [];
    $filterTanggal = $request->input('tanggal');

    foreach ($santris as $santri) {
        $row = [
            'nama' => $santri->nama_lengkap,
            'angkatan' => $santri->angkatan,
        ];

        foreach ($listUstadzah as $ustadzah) {
            $queryPenilaian = Penilaian::where('santriwati_id', $santri->id)
                ->where('user_id', $ustadzah->id);

            if ($filterTanggal) {
                $queryPenilaian->whereDate('tanggal', $filterTanggal);
            }

            // Jumlahkan semua kategori kategori (Dinamis)
            $row[$ustadzah->nama_lengkap] = $queryPenilaian->selectRaw('SUM(disiplin + k3 + tanggung_jawab + inisiatif_kreatifitas + adab + berterate + integritas_kesabaran + integritas_produktif + integritas_mandiri + integritas_optimis + integritas_kejujuran) as total')
                ->value('total') ?? 0;
        }
        $exportData[] = $row;
    }

    // 3. Nama File & Download
    $namaFile = 'Rekap-Penilaian-' . now()->format('d-m-Y') . '.xlsx';
    $namaUstadzahOnly = $listUstadzah->pluck('nama_lengkap')->toArray();

    return Excel::download(new PenilaianExport($exportData, $namaUstadzahOnly), $namaFile);
}

public function rekap(Request $request)
{
    // 1. Ambil pilihan Angkatan asli dari database
    $allAngkatan = \App\Models\Santriwati::distinct()->pluck('angkatan');

    // 2. Query data penilaian dengan relasi santriwati
    $query = \App\Models\Penilaian::with('santriwati');

    // 3. FITUR SEARCH NAMA (Baru)
    if ($request->filled('search')) {
        $query->whereHas('santriwati', function($q) use ($request) {
            $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
        });
    }

    // 4. Filter Angkatan
    if ($request->filled('angkatan')) {
        $query->whereHas('santriwati', function($q) use ($request) {
            $q->where('angkatan', $request->angkatan);
        });
    }

    // 5. Filter Tanggal
    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal', $request->tanggal);
    }

    // Ambil data terbaru
    $penilaians = $query->latest('tanggal')->get();

    return view('kesiswaan.penilaian.rekap', compact('penilaians', 'allAngkatan'));
}
}
