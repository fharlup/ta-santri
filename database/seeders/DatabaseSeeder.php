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
        // =================================================
        // 1. SEED ADMIN / STAFF
        // =================================================
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('123'),
                'role'     => 'Kesiswaan'
            ]
        );

        // =================================================
        // 2. SEED SANTRIWATI (MURID + AKUN)
        // =================================================
        $santriData = [
            [
                'nama'  => 'Aisyah Humaira',
                'nim'   => '2026001',
                'user'  => 'aisyah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID001'
            ],
            [
                'nama'  => 'Zahra Fatimah',
                'nim'   => '2026002',
                'user'  => 'zahra',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID002'
            ],
            [
                'nama'  => 'Khadijah Al-Kubra',
                'nim'   => '2026003',
                'user'  => 'khadijah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID003'
            ],
            [
                'nama'  => 'Siti Maryam',
                'nim'   => '2026004',
                'user'  => 'maryam',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID004'
            ],
            [
                'nama'  => 'Fatima Az-Zahra',
                'nim'   => '2026005',
                'user'  => 'fatima',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID005'
            ],
            [
                'nama'  => 'Hafsah',
                'nim'   => '2026006',
                'user'  => 'hafsah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID006'
            ],
            [
                'nama'  => 'Safiyyah',
                'nim'   => '2026007',
                'user'  => 'safiyyah',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID007'
            ],
            [
                'nama'  => 'Zaynab',
                'nim'   => '2026008',
                'user'  => 'zaynab',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID008'
            ],
            [
                'nama'  => 'Ruqayyah',
                'nim'   => '2026009',
                'user'  => 'ruqayyah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID009'
            ],
            [
                'nama'  => 'Umm Kulthum',
                'nim'   => '2026010',
                'user'  => 'kulthum',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID010'
            ],
            [
                'nama'  => 'Aminah',
                'nim'   => '2026011',
                'user'  => 'aminah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID011'
            ],
            [
                'nama'  => 'Barakah',
                'nim'   => '2026012',
                'user'  => 'barakah',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID012'
            ],
            [
                'nama'  => 'Sumayyah',
                'nim'   => '2026013',
                'user'  => 'sumayyah',
                'pass'  => 'santri123',
                'kelas' => '10-A',
                'rfid'  => 'RFID013'
            ],
            [
                'nama'  => 'Asiyah',
                'nim'   => '2026014',
                'user'  => 'asiyah',
                'pass'  => 'santri123',
                'kelas' => '11-B',
                'rfid'  => 'RFID014'
            ],
        ];

        foreach ($santriData as $data) {
            Santriwati::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama_lengkap' => $data['nama'],
                    'username'     => $data['user'],
                    'password'     => Hash::make($data['pass']),
                    'kelas'        => $data['kelas'],
                    'rfid_id'      => $data['rfid'],
                ]
            );
        }

        // =================================================
        // 3. SEED KEGIATAN
        // =================================================
        $kegiatan = Kegiatan::updateOrCreate(
            ['nama_kegiatan' => 'Tahfidz Pagi'],
            [
                'ustadzah_pendamping' => 'Usth. Sarah',
                'waktu_mulai'         => Carbon::today()->setTime(5, 0)
            ]
        );

        // =================================================
        // 4. SEED PRESENSI (7 HARI TERAKHIR - UNTUK CHART)
        // =================================================
        $santriAll = Santriwati::all();

        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i);

            foreach ($santriAll as $santri) {
                $nasib = rand(1, 10);

                if ($nasib <= 7) {
                    // 70% HADIR
                    Presensi::create([
                        'santriwati_id' => $santri->id,
                        'kegiatan_id'   => $kegiatan->id,
                        'waktu_scan'    => $tanggal->copy()->setTime(rand(4, 5), rand(0, 59)),
                        'status'        => 'Hadir'
                    ]);
                } elseif ($nasib <= 9) {
                    // 20% TERLAMBAT
                    Presensi::create([
                        'santriwati_id' => $santri->id,
                        'kegiatan_id'   => $kegiatan->id,
                        'waktu_scan'    => $tanggal->copy()->setTime(6, rand(0, 30)),
                        'status'        => 'Terlambat'
                    ]);
                }
                // 10% tidak hadir → tidak dibuat record
            }
        }
    }
}
