<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MEMBERSIHKAN DATA LAMA
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Santriwati::truncate();
        Kegiatan::truncate();
        Presensi::truncate();
        Penilaian::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. SEEDER ADMIN
        User::create([
            'nama_lengkap' => 'Admin Kesiswaan',
            'username' => 'admin',
            'password' => Hash::make('123'),
            'role' => 'Kesiswaan'
        ]);

        // 3. SEEDER DAFTAR NAMA SANTRIWATI
    $daftarNama = [
    'Siti Aminah', 'Fatimah Az-Zahra', 'Aisyah Humaira', 
    'Maryam Khairunnisa', 'Khadijah Al-Kubro'
];

foreach ($daftarNama as $index => $nama) {
    // Kita buat username simpel dari nama (huruf kecil, tanpa spasi)
    $username = strtolower(str_replace(' ', '', $nama));

    Santriwati::create([
        'nama_lengkap' => $nama,
        'nim'          => '103022' . rand(1000, 9999), // Contoh NIM acak
        'username'     => $username,
        'password'     => Hash::make('santri123'),    // Password default santri
        'kelas'        => '10A',
        'angkatan'     => '2024',
        'rfid_id'      => 'RFID-' . ($index + 100),   // SESUAI MIGRASI: rfid_id
    ]);
}
        // 4. SEEDER KEGIATAN & PRESENSI (7 Hari ke Belakang)
        $namaKegiatan = [
            'TAHAJJUD', 'SHUBUH', 'PIKET', 'APEL', 'HL DHUHA/KULIAH', 
            'SHOLAT DZUHUR', 'HL DZUHUR/KULIAH', 'ASHAR', 'BA/BM', 
            'MAGHRIB', 'ISYA', 'GH/M', 'KOMDIS'
        ];

        $jamKegiatan = [
            '03:30', '04:30', '05:30', '07:00', '08:00', '12:00', 
            '13:00', '15:30', '16:30', '18:00', '19:30', '20:30', '21:30'
        ];

        for ($d = 0; $d < 7; $d++) {
            $tgl = Carbon::today()->subDays($d);
            
            foreach ($namaKegiatan as $key => $nama) {
                $keg = Kegiatan::create([
                    'nama_kegiatan' => $nama,
                    'jam' => $jamKegiatan[$key],
                    'tanggal' => $tgl,
                    'angkatan' => '2024',
                    'ustadzah_1' => 'Ustadzah Fatimah',
                    'ustadzah_2' => 'Ustadzah Maryam'
                ]);

                // Simulasi Presensi otomatis untuk setiap santri yang baru dibuat
                foreach (Santriwati::all() as $santri) {
                    $prob = rand(1, 10);
                    if ($prob <= 9) { // 90% Hadir
                        $status = ($prob >= 8) ? 'TELAT' : 'HADIR';
                        
                        Presensi::create([
                            'santriwati_id' => $santri->id,
                            'kegiatan_id' => $keg->id,
                            'waktu_scan' => $tgl->copy()->setTimeFromTimeString($keg->jam)->addMinutes($status == 'TELAT' ? rand(11, 20) : rand(-10, 5)),
                            'status' => $status
                        ]);
                    }
                }
            }
        }
    }
}