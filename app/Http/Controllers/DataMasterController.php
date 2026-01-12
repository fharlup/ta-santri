<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santriwati;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Kegiatan;

class DataMasterController extends Controller
{
    /**
     * DASHBOARD: Menampilkan statistik utama
     */
public function dashboard()
{
    $today = now()->format('Y-m-d');

    // 1. Grafik Harian (Susun jam 04:00 hingga 21:00 supaya teratur)
    $chartData = ['labels' => [], 'values' => []];
    for ($h = 4; $h <= 21; $h++) {
        $labelJam = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        $chartData['labels'][] = $labelJam;
        $chartData['values'][] = \App\Models\Presensi::whereDate('waktu_scan', $today)
                                ->whereRaw('HOUR(waktu_scan) = ?', [$h])
                                ->count();
    }

    // 2. Grafik Mingguan (Selesaikan masalah 'Invalid Date')
    $weeklyData = ['labels' => [], 'values' => []];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        // Hantar label dalam bentuk String terus (contoh: "08 Jan")
        $weeklyData['labels'][] = $date->translatedFormat('d M'); 
        $weeklyData['values'][] = \App\Models\Presensi::whereDate('waktu_scan', $date->format('Y-m-d'))
                                 ->distinct('santriwati_id')->count();
    }

    $totalSantri = \App\Models\Santriwati::count();
    $hadirHariIni = \App\Models\Presensi::whereDate('waktu_scan', $today)->distinct('santriwati_id')->count();
    $terlambatHariIni = \App\Models\Presensi::whereDate('waktu_scan', $today)->where('status', 'TELAT')->count();
    $tidakHadir = $totalSantri - $hadirHariIni;

    return view('kesiswaan.dashboard', compact(
        'chartData', 'weeklyData', 'hadirHariIni', 
        'terlambatHariIni', 'tidakHadir', 'totalSantri'
    ));
}
    /**
     * INDEX: Daftar semua santriwati
     */

 public function index(Request $request)
{
    $allAngkatan = \App\Models\Santriwati::distinct()->pluck('angkatan');
    $query = \App\Models\Santriwati::query();

    // Logika Search Nama
    if ($request->filled('search')) {
        $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
    }

    // Filter Angkatan
    if ($request->filled('angkatan')) {
        $query->where('angkatan', $request->angkatan);
    }

    $santris = $query->latest()->paginate(20);

    return view('kesiswaan.santri.index', compact('santris', 'allAngkatan'));
}
    /**
     * CREATE: Form tambah santri (Sinkron dengan Master Angkatan & Kelas)
     */
    public function create()
    {
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();
        
        return view('kesiswaan.santri.create', compact('angkatans', 'kelas'));
    }

    /**
     * STORE: Simpan data santri baru
     */
   public function store(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'username'     => 'required|string|unique:santriwatis,username',
        'password'     => 'required|min:6',
        'rfid_id'      => 'required|string|unique:santriwatis,rfid_id',
        'angkatan'     => 'required',
        'kelas'        => 'required',
    ]);

    Santriwati::create([
        'nama_lengkap' => strtoupper($request->nama_lengkap),
        'nim'          => $request->nim,
        'username'     => $request->username,
        'password'     => $request->password,
        'rfid_id'      => $request->rfid_id,
        'angkatan'     => $request->angkatan,
        'kelas'        => $request->kelas,
    ]);

    return redirect()->route('santri.index')->with('success', 'Santriwati berhasil ditambahkan!');
}
    /**
     * EDIT: Form edit santri (Sinkron dengan Master Angkatan & Kelas)
     */
    public function edit($id)
    {
        $santri = Santriwati::findOrFail($id);
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();

        return view('kesiswaan.santri.edit', compact('santri', 'angkatans', 'kelas'));
    }

    /**
     * UPDATE: Perbarui data santri
     */
   public function update(Request $request, $id)
{
    $santri = Santriwati::findOrFail($id);

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        
        'username'     => 'required|string|unique:santriwatis,username,' . $id,
        'rfid_id'      => 'required|string|unique:santriwatis,rfid_id,' . $id,
        'angkatan'     => 'required',
        'kelas'        => 'required',
    ]);

    $data = [
        'nama_lengkap' => strtoupper($request->nama_lengkap),
        'username'     => $request->username,
        'rfid_id'      => $request->rfid_id,
        'angkatan'     => $request->angkatan,
        'kelas'        => $request->kelas,
    ];

    // Update password hanya jika diisi
    if ($request->filled('password')) {
        $data['password'] = $request->password;
    }

    $santri->update($data);

    return redirect()->route('santri.index')->with('success', 'Data santriwati diperbarui!');
}

    /**
     * DESTROY: Hapus data santri
     */
    public function destroy($id)
{
    $santri = Santriwati::findOrFail($id);

    // 1. Hapus semua data penilaian milik santri ini terlebih dahulu
    $santri->penilaians()->delete(); 

    // 2. Hapus semua data presensi milik santri ini (jika ada)
    $santri->presensis()->delete();

    // 3. Baru hapus data santrinya
    $santri->delete();

    return redirect()->route('santri.index')->with('success', 'Data santriwati dan seluruh riwayatnya telah dihapus!');
}
}