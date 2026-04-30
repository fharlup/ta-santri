<?php

namespace App\Http\Controllers;

use App\Models\Santriwati;
use App\Models\Presensi;
use App\Models\Kegiatan;
use App\Models\Angkatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapKegiatanController extends Controller
{
    /**
     * PAGE 1: PILIH ANAK
     * Menampilkan daftar santriwati untuk dipilih laporannya.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role == 'Santri' || auth()->user()->role == 'santri') {
            return redirect()->route('rekap.tahunan', auth()->user()->santriwati_id);
        }

        $query = Santriwati::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        $santris = $query->paginate(12);
        $allAngkatan = Angkatan::all();

        return view('kesiswaan.rekap.index', compact('santris', 'allAngkatan'));
    }

    /**
     * PAGE 2: OVERVIEW TAHUNAN (Jan - Des)
     * Menampilkan 12 bulan dengan progress bar 100%.
     */
    public function tahunan($santri_id, $tahun)
{
    $santri = Santriwati::findOrFail($santri_id);
    $rekapTahunan = [];

    for ($m = 1; $m <= 12; $m++) {
        // KUNCI UTAMA: Pastikan Carbon membuat tanggal berdasarkan $tahun yang dipilih
        $startDate = \Carbon\Carbon::create($tahun, $m, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($tahun, $m, 1)->endOfMonth();

        // Hitung kehadiran hanya pada rentang waktu di tahun tersebut
        $totalHadir = Presensi::where('santriwati_id', $santri_id)
            ->whereBetween('waktu_scan', [
                $startDate->toDateTimeString(), 
                $endDate->toDateTimeString()
            ])
            ->whereIn('status', ['HADIR', 'TELAT'])
            ->count();

        // Hitung total kegiatan wajib di bulan & tahun tersebut
        $totalKegiatan = Kegiatan::whereBetween('tanggal', [$startDate, $endDate])
            ->where('angkatan', $santri->angkatan)
            ->count();

        // Hitung persentase
        $persen = ($totalKegiatan > 0) ? ($totalHadir / $totalKegiatan) * 100 : 0;

        $rekapTahunan[] = [
            'bulan_ke' => $m,
            'nama_bulan' => $startDate->translatedFormat('F'),
            'persentase' => round($persen),
            'total_hadir' => $totalHadir,
            'total_kegiatan' => $totalKegiatan
        ];
    }

    return view('kesiswaan.rekap.tahunan', compact('rekapTahunan', 'santri', 'tahun'));
}

    /**
     * PAGE 3: DETAIL BULANAN (Logic Penurunan 25% Per Minggu)
     * Menghitung distribusi 25% dari kegiatan harian SI-DISIPLIN.
     */
    public function bulanan($santri_id, $bulan)
    {
        $tahun = date('Y');
        $santri = Santriwati::findOrFail($santri_id);
        $rekapMingguan = $this->hitungRekapMingguan($santri_id, $bulan, $tahun);
        
        $periodes = [
            1 => ['start' => 1, 'end' => 7],
            2 => ['start' => 8, 'end' => 14],
            3 => ['start' => 15, 'end' => 21],
            4 => ['start' => 22, 'end' => Carbon::create($tahun, $bulan)->endOfMonth()->day],
        ];

        $rekapMingguan = [];
        foreach ($periodes as $mingguKe => $range) {
            $totalPersenHarian = 0;
            $hariDalamMinggu = ($range['end'] - $range['start']) + 1;

            for ($d = $range['start']; $d <= $range['end']; $d++) {
                $tgl = Carbon::create($tahun, $bulan, $d)->format('Y-m-d');
                
                $hadir = Presensi::where('santriwati_id', $santri_id)
                        ->whereDate('waktu_scan', $tgl)
                        ->whereIn('status', ['HADIR', 'TELAT'])->count();
                
                // Ada 13 kegiatan harian sesuai sistem SI-DISIPLIN.
                $totalPersenHarian += ($hadir / 13) * 100;
            }
            
            // Rumus: (Rata-rata harian) x bobot 25%.
            $skorMinggu = ($totalPersenHarian / $hariDalamMinggu) * 0.25;
            
            $rekapMingguan[] = [
                'minggu' => $mingguKe,
                'skor' => round($skorMinggu, 1),
                'rentang' => $range['start'] . " - " . $range['end'] . " " . Carbon::create(null, $bulan)->format('M')
            ];
        }

        return view('kesiswaan.rekap.bulanan', compact('santri', 'rekapMingguan', 'bulan', 'tahun'));
    }
public function mingguan($santri_id, $bulan, $minggu, $tahun)
{
    $santri = \App\Models\Santriwati::findOrFail($santri_id);

    // 1. Tentukan rentang hari berdasarkan minggu (7 hari per minggu)
    $startDay = ($minggu - 1) * 7 + 1;
    $rekapHarian = [];

    // 2. Loop selama 7 hari untuk mengisi detail mingguan
    for ($i = 0; $i < 7; $i++) {
        $currentDate = \Carbon\Carbon::create($tahun, $bulan, $startDay + $i);
        
        // Pastikan tidak melewati batas hari di bulan tersebut
        if ($currentDate->month != $bulan) break;

        // 3. Ambil data presensi untuk 13 kegiatan di hari tersebut
        $presensiHariIni = \App\Models\Presensi::where('santriwati_id', $santri_id)
            ->whereDate('waktu_scan', $currentDate->toDateString())
            ->get();

        // 4. Hitung persentase harian
        $hadirCount = $presensiHariIni->whereIn('status', ['HADIR', 'TELAT'])->count();
        $persentase = (13 > 0) ? round(($hadirCount / 13) * 100) : 0;

        // 5. Mapping data kegiatan (Sesuaikan dengan nama kegiatan di sistem Anda)
        $daftarKegiatan = [
            'Tahajud', 'Subuh', 'Dzikir Pagi', 'Tahfidz 1', 'Dhuha', 
            'Pelajaran 1', 'Dzuhur', 'Pelajaran 2', 'Ashar', 
            'Dzikir Sore', 'Maghrib', 'Isya', 'Tahfidz 2'
        ];

        $detailKegiatan = [];
        foreach ($daftarKegiatan as $nama) {
            $p = $presensiHariIni->where('nama_kegiatan', $nama)->first();
            $detailKegiatan[] = [
                'nama' => $nama,
                'status' => $p ? $p->status : 'ALPHA'
            ];
        }

        $rekapHarian[] = [
            'hari' => $currentDate->translatedFormat('l'),
            'tanggal' => $currentDate->translatedFormat('d F Y'),
            'hadir_count' => $hadirCount,
            'persentase' => $persentase,
            'kegiatan' => $detailKegiatan
        ];
    }

    // KUNCI: Pastikan 'rekapHarian' dan 'tahun' masuk ke compact!
    return view('kesiswaan.rekap.mingguan', compact(
        'santri', 
        'rekapHarian', 
        'bulan', 
        'minggu', 
        'tahun'
    ));
}
    public function pilihTahun($santri_id)
{
    $santri = Santriwati::findOrFail($santri_id);
    
    // Logika: Ambil tahun masuk dari kolom angkatan (misal: 2024)
    $tahunMasuk = (int) $santri->angkatan; 
    
    // Hasilkan daftar 3 tahun masa studi (Tahun ke-1, ke-2, ke-3)
    $daftarTahun = [
        ['label' => 'Tahun 1', 'value' => $tahunMasuk],
        ['label' => 'Tahun 2', 'value' => $tahunMasuk + 1],
        ['label' => 'Tahun 3', 'value' => $tahunMasuk + 2],
    ];

    return view('kesiswaan.rekap.pilih_tahun', compact('santri', 'daftarTahun'));
}

private function hitungRekapMingguan($santri_id, $bulan, $tahun)
{
    $rekap = [];
    
    for ($w = 1; $w <= 4; $w++) {
        // Tentukan tanggal awal minggu (1, 8, 15, 22)
        $day = ($w - 1) * 7 + 1;
        
        // Kunci: Gunakan Carbon::create($tahun, $bulan, $day) agar unik tiap loop
        $startDate = \Carbon\Carbon::create($tahun, $bulan, $day)->startOfDay();
        
        // Akhir minggu adalah 6 hari setelah start date
        $endDate = $startDate->copy()->addDays(6)->endOfDay();

        // Pastikan query difilter ketat berdasarkan range minggu ini
        $totalHadir = \App\Models\Presensi::where('santriwati_id', $santri_id)
            ->whereBetween('waktu_scan', [$startDate, $endDate])
            ->whereIn('status', ['HADIR', 'TELAT'])
            ->count();

        // Asumsi 13 kegiatan x 7 hari = 91 kegiatan per minggu
        $totalKegiatan = 91; 

        $skor = ($totalKegiatan > 0) ? ($totalHadir / $totalKegiatan) * 25 : 0;

        $rekap[] = [
            'minggu' => $w,
            'rentang' => $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M'),
            'skor' => round($skor, 1)
        ];
    }
    return $rekap;
}
public function penilaianIndex($santri_id, $tahun, $bulan)
{
    $santri = \App\Models\Santriwati::findOrFail($santri_id);
    
    // Ambil satu data penilaian untuk santri ini di bulan & tahun terkait
    // Kita gunakan whereMonth dan whereYear pada kolom 'tanggal'
    $penilaian = \App\Models\Penilaian::where('santriwati_id', $santri_id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->first();

    // Mapping Label Tabel ke Nama Kolom di Database
    $aspekPenilaian = [
        'Kedisiplinan' => 'disiplin',
        'Kebersihan, Kesehatan, Keindahan (K3)' => 'k3',
        'Tanggung Jawab' => 'tanggung_jawab',
        'Inisiatif & Kreatifitas' => 'inisiatif_kreatifitas',
        'Adab & Akhlaq' => 'adab',
        'Berterate' => 'berterate',
        'Integritas (Kesabaran)' => 'integritas_kesabaran',
        'Integritas (Produktif)' => 'integritas_produktif',
        'Integritas (Mandiri)' => 'integritas_mandiri',
        'Integritas (Optimis)' => 'integritas_optimis',
        'Integritas (Kejujuran)' => 'integritas_kejujuran',
    ];

    return view('kesiswaan.rekap.penilaian_index', compact(
        'santri', 
        'penilaian', 
        'aspekPenilaian', 
        'tahun', 
        'bulan'
    ));
}
}
