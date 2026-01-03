<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DataMasterController extends Controller
{
    /**
     * FR-02: Menampilkan Dashboard Utama
     */
    public function dashboard()
    {
        $totalSantri = Santriwati::count();
        $hadirHariIni = Presensi::whereDate('waktu_scan', Carbon::today())->where('status', 'Hadir')->count();
        $terlambatHariIni = Presensi::whereDate('waktu_scan', Carbon::today())->where('status', 'Terlambat')->count();
        $tidakHadir = $totalSantri - ($hadirHariIni + $terlambatHariIni);

        // Data Grafik Harian (Per Jam)
        $chartData = Presensi::whereDate('waktu_scan', Carbon::today())
            ->selectRaw('HOUR(waktu_scan) as hour, count(*) as count')
            ->groupBy('hour')->pluck('count', 'hour')->all();

        // Data Grafik Mingguan (7 Hari Terakhir)
        $weeklyData = Presensi::where('waktu_scan', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(waktu_scan) as date, count(*) as count')
            ->groupBy('date')->orderBy('date')->pluck('count', 'date')->all();

        return view('kesiswaan.dashboard', compact(
            'totalSantri', 'hadirHariIni', 'terlambatHariIni', 'tidakHadir', 'chartData', 'weeklyData'
        ));
    }

    /**
     * MENAMPILKAN DAFTAR SANTRIWATI (Index)
     * Error sebelumnya terjadi karena fungsi ini belum ada.
     */
    public function index(Request $request)
    {
        $query = Santriwati::query();

        // Fitur Pencarian Nama atau NIM
        if ($request->has('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
        }

        $santris = $query->latest()->get();

        // Mengarah ke resources/views/kesiswaan/santri/index.blade.php
        return view('kesiswaan.santri.index', compact('santris'));
    }

    /**
     * MENAMPILKAN FORM TAMBAH
     */
    public function create()
    {
        return view('kesiswaan.santri.create');
    }

    /**
     * MENYIMPAN DATA SANTRI BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim'          => 'required|unique:santriwatis,nim',
            'username'     => 'required|unique:santriwatis,username',
            'password'     => 'required|min:6',
            'kelas'        => 'required',
            'rfid_id'      => 'required|unique:santriwatis,rfid_id',
        ]);

        $validated['password'] = Hash::make($request->password); // Enkripsi password

        Santriwati::create($validated);

        return redirect()->route('santri.index')->with('success', 'Data Santriwati berhasil disimpan!');
    }

    /**
     * MENAMPILKAN FORM EDIT
     */
    public function edit($id)
    {
        $santri = Santriwati::findOrFail($id);
        return view('kesiswaan.santri.edit', compact('santri'));
    }

    /**
     * MEMPROSES UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $santri = Santriwati::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim'          => ['required', Rule::unique('santriwatis')->ignore($santri->id)],
            'username'     => ['required', Rule::unique('santriwatis')->ignore($santri->id)],
            'kelas'        => 'required',
            'rfid_id'      => ['required', Rule::unique('santriwatis')->ignore($santri->id)],
            'password'     => 'nullable|min:6', // Password opsional saat edit
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // Jangan update password jika kosong
        }

        $santri->update($validated);

        return redirect()->route('santri.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * MENGHAPUS DATA SANTRI
     */
    public function destroy($id)
    {
        Santriwati::findOrFail($id)->delete();
        return redirect()->route('santri.index')->with('success', 'Data berhasil dihapus!');
    }
}