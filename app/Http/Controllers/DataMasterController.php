<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use Carbon\Carbon;

class DataMasterController extends Controller
{
    // Fungsi untuk Dashboard (FR-02)
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

    return view('kesiswaan.dashboard', compact('totalSantri', 'hadirHariIni', 'terlambatHariIni', 'tidakHadir', 'chartData', 'weeklyData'));
       
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