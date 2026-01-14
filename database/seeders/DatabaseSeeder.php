<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // WAJIB TAMBAH INI
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- MEMBERSIHKAN TABEL SEBELUM SEEDING (ANTI DOUBLE) ---
        Schema::disableForeignKeyConstraints(); // Matikan proteksi relasi sementara
        
        Kegiatan::truncate(); // Hapus semua data kegiatan lama
        Presensi::truncate(); // Hapus semua data presensi lama
        Penilaian::truncate(); // Hapus semua data penilaian lama
        
        Schema::enableForeignKeyConstraints(); // Nyalakan kembali proteksi
        // --------------------------------------------------------

        // 1. SEEDER STAFF/ADMIN
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Admin Kesiswaan',
                'password' => Hash::make('123'),
                'role' => 'Kesiswaan'
            ]
        );

        // 3. SEEDER 13 KEGIATAN WAJIB (Loop 7 Hari ke Belakang untuk Chart) 
        $namaKegiatan = [
            'TAHAJJUD', 'SHUBUH', 'PIKET', 'APEL', 'HL DHUHA/KULIAH', 
            'SHOLAT DZUHUR', 'HL DZUHUR/KULIAH', 'ASHAR', 'BA/BM', 
            'MAGHRIB', 'ISYA', 'GH/M', 'KOMDIS'
        ];

        $jamKegiatan = [
            '03:30', '04:30', '05:30', '07:00', '08:00', 
            '12:00', '13:00', '15:30', '16:30', 
            '18:00', '19:30', '20:30', '21:30'
        ];

        for ($d = 0; $d < 7; $d++) {
            $tgl = Carbon::today()->subDays($d);
            
            foreach ($namaKegiatan as $key => $nama) {
                // Gunakan create untuk data simulasi harian
                $keg = Kegiatan::create([
                    'nama_kegiatan' => $nama,
                    'jam' => $jamKegiatan[$key],
                    'tanggal' => $tgl,
                    'angkatan' => '2024',
                    'ustadzah_1' => 'Ustadzah Fatimah', //
                    'ustadzah_2' => 'Ustadzah Maryam'
                ]);

                // 4. SEEDER PRESENSI (Simulasi untuk Chart %)
                foreach (Santriwati::all() as $santri) {
                    $probabilitas = rand(1, 10);
                    if ($probabilitas <= 9) { // 90% santri melakukan tapping
                        
                        // Logika HADIR vs TELAT (Toleransi 10 Menit)
                        $status = ($probabilitas >= 8) ? 'TELAT' : 'HADIR';
                        
                        Presensi::create([
                            'santriwati_id' => $santri->id,
                            'kegiatan_id' => $keg->id,
                            'waktu_scan' => $tgl->copy()->setTimeFromTimeString($keg->jam)->addMinutes($status == 'TELAT' ? rand(11, 20) : rand(-10, 5)),
                            'status' => $status,
                            'keterangan' => $status == 'TELAT' ? 'Kesiangan' : null
                        ]);
                    }
                }
            }
        }

        // 5. SEEDER PENILAIAN KARAKTER (A/B/C)
        foreach (Santriwati::all() as $santri) {
            Penilaian::create([
                'santriwati_id' => $santri->id,
                'tanggal' => Carbon::today(),
                'angkatan' => $santri->angkatan,
                'disiplin' => $santri->id % 2 == 0 ? 'A' : 'B', // A: Sangat Terbina, B: Terbina
                'k3' => 'B',
                'tanggung_jawab' => 'B',
                'inisiatif_kreatifitas' => 'B',
                'adab' => 'A',
                'berterate' => 'B',
                'integritas_kesabaran' => 'A',
                'integritas_produktif' => 'B',
                'integritas_mandiri' => 'B',
                'integritas_optimis' => 'A',
                'integritas_kejujuran' => 'A',
                'deskripsi' => 'Menunjukkan perkembangan karakter yang stabil.'
            ]);
        }
    }
}