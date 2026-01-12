<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santriwati;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DataMasterController extends Controller
{
    /**
     * DASHBOARD: Menampilkan statistik harian & grafik
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // --- 1. LOGIKA UNTUK ROLE SANTRI ---
        if ($user->role == 'Santri') {
            if (!$user->santriwati_id) {
                return "Akun Anda belum terhubung dengan data Santriwati. Silakan hubungi bagian Kesiswaan.";
            }

            $data = [
                'total_hadir' => Presensi::where('santriwati_id', $user->santriwati_id)
                                ->where('status', 'Hadir')->count(),
                'nilai_terakhir' => Penilaian::where('santriwati_id', $user->santriwati_id)
                                ->latest()->first(),
                'presensi' => Presensi::where('santriwati_id', $user->santriwati_id)
                                ->with('kegiatan')->latest()->take(5)->get(),
            ];

            return view('kesiswaan.dashboard', compact('data'));
        }

        // --- 2. LOGIKA UNTUK ROLE ADMIN (KESISWAAN/KOMDIS/WALI KELAS) ---
        $chartData = ['labels' => [], 'values' => []];
        for ($h = 4; $h <= 21; $h++) {
            $labelJam = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $chartData['labels'][] = $labelJam;
            $chartData['values'][] = Presensi::whereDate('waktu_scan', $today)
                                    ->whereRaw('HOUR(waktu_scan) = ?', [$h])
                                    ->count();
        }

        $weeklyData = ['labels' => [], 'values' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyData['labels'][] = $date->translatedFormat('d M'); 
            $weeklyData['values'][] = Presensi::whereDate('waktu_scan', $date->format('Y-m-d'))
                                     ->distinct('santriwati_id')->count();
        }

        $totalSantri = Santriwati::count();
        $hadirHariIni = Presensi::whereDate('waktu_scan', $today)->distinct('santriwati_id')->count();
        $terlambatHariIni = Presensi::whereDate('waktu_scan', $today)->where('status', 'TELAT')->count();
        $tidakHadir = max(0, $totalSantri - $hadirHariIni);

        return view('kesiswaan.dashboard', compact(
            'chartData', 'weeklyData', 'hadirHariIni', 
            'terlambatHariIni', 'tidakHadir', 'totalSantri'
        ));
    }

    /**
     * INDEX: Daftar santriwati dengan fitur Search & Filter
     */
    public function index(Request $request)
    {
        $allAngkatan = Angkatan::all();
        $query = Santriwati::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        $santris = $query->latest()->paginate(20);

        return view('kesiswaan.santri.index', compact('santris', 'allAngkatan'));
    }

    public function create()
    {
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();
        return view('kesiswaan.santri.create', compact('angkatans', 'kelas'));
    }

    /**
     * STORE: Simpan santri baru ke database (Sudah ada NIM)
     */
    

    public function edit($id)
    {
        $santri = Santriwati::findOrFail($id);
        $angkatans = Angkatan::all();
        $kelas = Kelas::all();
        return view('kesiswaan.santri.edit', compact('santri', 'angkatans', 'kelas'));
    }

    /**
     * UPDATE: Perbarui data santri (Sudah ada NIM)
     */
    public function update(Request $request, $id)
    {
        $santri = Santriwati::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim'          => 'required|string|unique:santriwatis,nim,' . $id, // Validasi NIM unik
            'username'     => 'required|string|unique:santriwatis,username,' . $id,
            'rfid_id'      => 'required|string|unique:santriwatis,rfid_id,' . $id,
            'angkatan'     => 'required',
            'kelas'        => 'required',
        ]);

        $data = [
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'nim'          => $request->nim, // Update NIM
            'username'     => $request->username,
            'rfid_id'      => $request->rfid_id,
            'angkatan'     => $request->angkatan,
            'kelas'        => $request->kelas,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $santri->update($data);

        return redirect()->route('santri.index')->with('success', 'Data santriwati diperbarui!');
    }

    public function destroy($id)
    {
        $santri = Santriwati::findOrFail($id);
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data santriwati berhasil dihapus!');
    }
    public function store(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nim'          => 'required|string|unique:santriwatis,nim',
        'username'     => 'required|string|unique:users,username', // Cek unik di tabel users
        'password'     => 'required|min:6',
        'rfid_id'      => 'required|string|unique:santriwatis,rfid_id',
        'angkatan'     => 'required',
        'kelas'        => 'required',
    ]);

    // 1. Simpan ke tabel santriwatis (Data Administratif)
    $santri = \App\Models\Santriwati::create([
        'nama_lengkap' => strtoupper($request->nama_lengkap),
        'nim'          => $request->nim,
        'username'     => $request->username,
        'password'     => $request->password, // Hanya arsip (bukan untuk login)
        'rfid_id'      => $request->rfid_id,
        'angkatan'     => $request->angkatan,
        'kelas'        => $request->kelas,
    ]);

    // 2. OTOMATIS: Simpan ke tabel users agar BISA LOGIN
    \App\Models\User::create([
        'nama_lengkap'  => $santri->nama_lengkap,
        'username'      => $request->username,
        'password'      => \Illuminate\Support\Facades\Hash::make($request->password), // Di-hash otomatis
        'role'          => 'Santri',
        'santriwati_id' => $santri->id, // Jembatan ID otomatis
    ]);

    return redirect()->route('santri.index')->with('success', 'Santriwati dan Akun Login berhasil dibuat!');
}
}