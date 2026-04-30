<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekap_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
public function tahunan($santri_id)
{
    $santri = \App\Models\Santriwati::findOrFail($santri_id);
    $tahun = date('Y');
    $rekapTahunan = [];

    // Loop dari bulan 1 (Januari) sampai 12 (Desember)
    for ($m = 1; $m <= 12; $m++) {
        $startDate = \Carbon\Carbon::create($tahun, $m, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($tahun, $m, 1)->endOfMonth();

        // Hitung total kehadiran santri di bulan tersebut
        $totalHadir = \App\Models\Presensi::where('santriwati_id', $santri_id)
            ->whereBetween('waktu_scan', [$startDate, $endDate])
            ->whereIn('status', ['HADIR', 'TELAT'])
            ->count();

        // Hitung total kegiatan yang tersedia di bulan tersebut
        $totalKegiatan = \App\Models\Kegiatan::whereBetween('tanggal', [$startDate, $endDate])
            ->where('angkatan', $santri->angkatan)
            ->count();

        // Hitung Persentase (Jika tidak ada kegiatan, set 0)
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kegiatans');
    }
};
