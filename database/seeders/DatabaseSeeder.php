<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEEDER USER (STAFF/KESISWAAN)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('123'),
                'role' => 'Kesiswaan'
            ]
        );

        // 2. SEEDER SANTRIWATI (AKUN LOGIN MURID)
        $dataSantri = [
            [
                'nama' => 'Aisyah Humaira',
                'nim' => '2026001',
                'user' => 'aisyah',
                'pass' => 'santri123',
                'kelas' => '10-A',
                'rfid' => 'RFID-998877'
            ],
            [
                'nama' => 'Zahra Fatimah',
                'nim' => '2026002',
                'user' => 'zahra',
                'pass' => 'santri123',
                'kelas' => '11-B',
                'rfid' => 'RFID-112233'
            ],
            [
                'nama' => 'Khadijah Al-Kubra',
                'nim' => '2026003',
                'user' => 'khadijah',
                'pass' => 'santri123',
                'kelas' => '10-A',
                'rfid' => 'RFID-445566'
            ],
        ];

        foreach ($dataSantri as $ds) {
            Santriwati::updateOrCreate(
                ['nim' => $ds['nim']],
                [
                    'nama_lengkap' => $ds['nama'],
                    'username'     => $ds['user'],
                    'password'     => Hash::make($ds['pass']), // Password dienkripsi
                    'kelas'        => $ds['kelas'],
                    'rfid_id'      => $ds['rfid'],
                ]
            );
        }

        // 3. SEEDER JADWAL KEGIATAN
        $jadwalKegiatan = [
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

        foreach ($jadwalKegiatan as $jk) {
            Kegiatan::updateOrCreate(
                ['nama_kegiatan' => $jk['nama']],
                ['jam' => $jk['jam']]
            );
        }
    }
}