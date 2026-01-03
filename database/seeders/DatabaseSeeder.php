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
        // 1. BUAT USER (Gunakan updateOrCreate agar tidak error duplikat jika di-run manual)
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('123'), 'role' => 'Kesiswaan']
        );

        // 2. BUAT DATA SANTRIWATI (Contoh 14 Santri sesuai mockup)
        $daftarNama = [
            'Aisyah Humaira', 'Zahra Fatimah', 'Khadijah Al-Kubra', 'Siti Maryam', 
            'Fatima Az-Zahra', 'Sumayyah', 'Asiyah', 'Hafsah', 'Safiyyah', 
            'Zaynab', 'Ruqayyah', 'Umm Kulthum', 'Aminah', 'Barakah'
        ];

        foreach ($daftarNama as $index => $nama) {
            Santriwati::updateOrCreate(
                ['nim' => '2026' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                ['nama_lengkap' => $nama, 'kelas' => '10-A', 'rfid_id' => 'RFID' . ($index + 1)]
            );
        }

        // 3. BUAT KEGIATAN
        $kegiatan = Kegiatan::updateOrCreate(
            ['nama_kegiatan' => 'Tahfidz Pagi'],
            ['ustadzah_pendamping' => 'Usth. Sarah', 'waktu_mulai' => Carbon::today()->setTime(5, 0)]
        );

        // 4. SEED DATA PRESENSI UNTUK GRAFIK (7 Hari Terakhir)
        $santriAll = Santriwati::all();
        
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i);
            
            foreach ($santriAll as $santri) {
                // Acak: Ada yang hadir, ada yang terlambat, ada yang tidak hadir (bolos)
                $nasib = rand(1, 10);
                
                if ($nasib <= 7) { // 70% Hadir Tepat Waktu
                    Presensi::create([
                        'santriwati_id' => $santri->id,
                        'kegiatan_id' => $kegiatan->id,
                        'waktu_scan' => $tanggal->copy()->setTime(rand(4, 5), rand(0, 59)),
                        'status' => 'Hadir'
                    ]);
                } elseif ($nasib <= 9) { // 20% Terlambat
                    Presensi::create([
                        'santriwati_id' => $santri->id,
                        'kegiatan_id' => $kegiatan->id,
                        'waktu_scan' => $tanggal->copy()->setTime(6, rand(0, 30)),
                        'status' => 'Terlambat'
                    ]);
                } 
                // 10% sisanya dianggap tidak hadir (tidak ada record presensi)
            }
        }
    }
}