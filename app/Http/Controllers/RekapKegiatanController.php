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

        return view('kesiswaan.rekap.bulanan', compact('rekapMingguan', 'santri', 'bulan'));
    }
public function mingguan($santri_id, $bulan, $minggu)
{
    $santri = Santriwati::findOrFail($santri_id);
    $tahun = date('Y');
    
    $periodes = [
        1 => ['start' => 1, 'end' => 7],
        2 => ['start' => 8, 'end' => 14],
        3 => ['start' => 15, 'end' => 21],
        4 => ['start' => 22, 'end' => Carbon::create($tahun, $bulan)->endOfMonth()->day],
    ];

    $range = $periodes[$minggu];
    $kegiatans = [
        'TAHAJJUD', 'SHUBUH', 'PIKET', 'APEL', 'HL DHUHA/KULIAH', 
        'SHOLAT DZUHUR', 'HL DZUHUR/KULIAH', 'ASHAR', 'BA/BM', 
        'MAGHRIB', 'ISYA', 'GH/M', 'KOMDIS'
    ]; 
    
    $rekapHarian = [];

    for ($d = $range['start']; $d <= $range['end']; $d++) {
        $date = Carbon::create($tahun, $bulan, $d);
        $tglString = $date->format('Y-m-d');
        
        $detailKegiatan = [];
        $countHadir = 0;

        foreach ($kegiatans as $nama) {
            $presensi = Presensi::where('santriwati_id', $santri_id)
                ->whereDate('waktu_scan', $tglString)
                ->whereHas('kegiatan', function($q) use ($nama) {
                    $q->where('nama_kegiatan', $nama);
                })->first();

            $status = $presensi ? $presensi->status : 'ALPHA';
            if (in_array($status, ['HADIR', 'TELAT'])) {
                $countHadir++;
            }

            $detailKegiatan[] = [
                'nama' => $nama,
                'status' => $status
            ];
        }

        // Hitung persen harian: (Hadir / 13) * 100
        $persenHarian = round(($countHadir / 13) * 100);

        $rekapHarian[] = [
            'hari' => $date->translatedFormat('l'),
            'tanggal' => $date->translatedFormat('d F Y'),
            'hadir_count' => $countHadir,
            'persentase' => $persenHarian,
            'kegiatan' => $detailKegiatan
        ];
    }

    return view('kesiswaan.rekap.mingguan', compact('rekapHarian', 'santri', 'bulan', 'minggu'));
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
}
