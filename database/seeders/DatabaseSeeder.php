<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEEDER PENGGUNA (STAFF/ADMIN)
        $staffs = [
            ['nama' => 'Ustadzah Fatimah', 'user' => 'admin', 'role' => 'Kesiswaan'],
            ['nama' => 'Ustadzah Sarah', 'user' => 'sarah', 'role' => 'Komdis'],
            ['nama' => 'Ustadzah Aminah', 'user' => 'aminah', 'role' => 'Musyrifah'],
        ];

        foreach ($staffs as $s) {
            User::updateOrCreate(
                ['username' => $s['user']],
                [
                    'nama_lengkap' => $s['nama'],
                    'password' => Hash::make('123'),
                    'role' => $s['role']
                ]
            );
        }

        // 2. SEEDER SANTRIWATI (AKUN LOGIN MURID)
        $daftarSantri = [
            ['nama' => 'Aisyah Humaira', 'nim' => '2026001', 'user' => 'aisyah', 'rfid' => 'RFID001'],
            ['nama' => 'Zahra Fatimah', 'nim' => '2026002', 'user' => 'zahra', 'rfid' => 'RFID002'],
            ['nama' => 'Khadijah Al-Kubra', 'nim' => '2026003', 'user' => 'khadijah', 'rfid' => 'RFID003'],
            ['nama' => 'Siti Maryam', 'nim' => '2026004', 'user' => 'siti', 'rfid' => 'RFID004'],
            ['nama' => 'Fatima Az-Zahra', 'nim' => '2026005', 'user' => 'fatima', 'rfid' => 'RFID005'],
        ];

        foreach ($daftarSantri as $ds) {
            Santriwati::updateOrCreate(
                ['nim' => $ds['nim']],
                [
                    'nama_lengkap' => $ds['nama'],
                    'username'     => $ds['user'],
                    'password'     => Hash::make('santri123'),
                    'kelas'        => '10-A',
                    'rfid_id'      => $ds['rfid'],
                ]
            );
        }

        // 3. SEEDER JADWAL KEGIATAN
        $jadwal = [
            ['nama' => 'Sholat Tahajud', 'jam' => '02:30'],
            ['nama' => 'Shubuh', 'jam' => '04:15'],
            ['nama' => 'Piket', 'jam' => '06:00'],
            ['nama' => 'Apel', 'jam' => '08:00'],
            ['nama' => 'HL Dhuha/Kuliah', 'jam' => '08:30'],
            ['nama' => 'Sholat Dzuhur', 'jam' => '12:00'],
            ['nama' => 'HL Dzuhur/kuliah', 'jam' => '13:00'],
            ['nama' => 'Ashar', 'jam' => '15:00'],
            ['nama' => 'BA/BM', 'jam' => '15:45'],
            ['nama' => 'Sholat Magrib', 'jam' => '18:00'],
            ['nama' => 'Sholat Isya', 'jam' => '19:00'],
            ['nama' => 'GH/M', 'jam' => '20:00'],
            ['nama' => 'KOMDIS', 'jam' => '21:30'],
        ];

        foreach ($jadwal as $j) {
            Kegiatan::updateOrCreate(
                ['nama_kegiatan' => $j['nama']],
                ['jam' => $j['jam']]
            );
        }

        // 4. SEEDER DATA PRESENSI UNTUK GRAFIK (7 HARI TERAKHIR)
        $allSantri = Santriwati::all();
        $allKegiatan = Kegiatan::all();
        
        // Loop untuk 7 hari terakhir
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i);
            
            foreach ($allKegiatan as $keg) {
                foreach ($allSantri as $santri) {
                    // Simulasi: 80% Hadir, 10% Terlambat, 10% Tidak Hadir
                    $rand = rand(1, 10);
                    
                    if ($rand <= 9) { // Hadir atau Terlambat
                        $status = ($rand == 9) ? 'Terlambat' : 'Hadir';
                        
                        // Set waktu scan sekitar jam kegiatan
                        $jamKegiatan = Carbon::parse($keg->jam);
                        $waktuScan = $tanggal->copy()->setTime(
                            $jamKegiatan->hour, 
                            $jamKegiatan->minute + ($status == 'Terlambat' ? rand(16, 30) : rand(-15, 15))
                        );

                        Presensi::create([
                            'santriwati_id' => $santri->id,
                            'kegiatan_id' => $keg->id,
                            'waktu_scan' => $waktuScan,
                            'status' => $status
                        ]);
                    }
                }
            }
        }
    }
}