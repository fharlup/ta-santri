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
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Admin Kesiswaan',
                'password' => Hash::make('123'),
                'role' => 'Kesiswaan'
            ]
        );

        // 2. SEEDER SANTRIWATI (CONTOH DATA)
        $santris = [
            ['nama' => 'Aisyah Humaira', 'nim' => '2026001', 'user' => 'aisyah', 'rfid' => 'RFID001', 'kelas' => '10-A'],
            ['nama' => 'Zahra Fatimah', 'nim' => '2026002', 'user' => 'zahra', 'rfid' => 'RFID002', 'kelas' => '11-B'],
            ['nama' => 'Khadijah Al-Kubra', 'nim' => '2026003', 'user' => 'khadijah', 'rfid' => 'RFID003', 'kelas' => '10-A'],
            ['nama' => 'Fauzi Taufiq', 'nim' => '2026004', 'user' => 'fauzi', 'rfid' => 'RFID004', 'kelas' => 'TI-46-05'],
        ];

        foreach ($santris as $s) {
            Santriwati::updateOrCreate(
                ['nim' => $s['nim']],
                [
                    'nama_lengkap' => $s['nama'],
                    'username' => $s['user'],
                    'password' => Hash::make('santri123'),
                    'kelas' => $s['kelas'],
                    'rfid_id' => $s['rfid'],
                ]
            );
        }

        // 3. SEEDER JADWAL KEGIATAN LENGKAP
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

        // 4. SEEDER SIMULASI PRESENSI (7 HARI TERAKHIR)
        $allSantri = Santriwati::all();
        $allKegiatan = Kegiatan::all();
        
        // Loop 7 hari ke belakang
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i);
            
            foreach ($allKegiatan as $keg) {
                foreach ($allSantri as $santri) {
                    // Simulasi: 70% Hadir, 20% Terlambat, 10% Tidak Absen
                    $random = rand(1, 10);
                    
                    if ($random <= 9) {
                        $status = ($random >= 8) ? 'Terlambat' : 'Tepat Waktu';
                        $jamKegiatan = Carbon::parse($keg->jam);
                        
                        // Set waktu scan (Tepat waktu = sebelum/pas, Terlambat = lewat 15-30 mnt)
                        $waktuScan = $tanggal->copy()->setTime(
                            $jamKegiatan->hour, 
                            $jamKegiatan->minute + ($status == 'Terlambat' ? rand(16, 40) : rand(-10, 15))
                        );

                        Presensi::create([
                            'santriwati_id' => $santri->id,
                            'kegiatan_id' => $keg->id,
                            'waktu_scan' => $waktuScan,
                            'status' => $status,
                            'keterangan' => ($status == 'Terlambat') ? 'telat bangun tidur' : null //
                        ]);
                    }
                }
            }
        }
    }
}