<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PresensiTest extends TestCase
{
    use RefreshDatabase; // Membersihkan database setiap kali test dijalankan

    /** @test */
    public function santri_berhasil_scan_dan_status_hadir()
    {
        // 1. Persiapan Data (Setup)
        $user = User::factory()->create(['role' => 'Kesiswaan']);
        $santri = Santriwati::create([
            'nama_lengkap' => 'Naila Khairunnisa',
            'rfid_id' => '1234567890',
            'angkatan' => '2024'
        ]);

        // Buat kegiatan jam 05:00 (Gunakan kolom 'jam' sesuai DB Anda)
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => 'SHUBUH',
            'jam' => '05:00:00',
            'tanggal' => now()->toDateString()
        ]);

        // Simulasikan waktu saat ini adalah jam 05:05 (Masih dalam toleransi 10 menit)
        Carbon::setTestNow(now()->setTime(5, 5));

        // 2. Aksi (Action)
        $response = $this->actingAs($user)
                         ->post(route('presensi.check'), [
                             'rfid' => '1234567890'
                         ]);

        // 3. Verifikasi (Assert)
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('presensis', [
            'santriwati_id' => $santri->id,
            'status' => 'HADIR'
        ]);
    }

    /** @test */
    public function santri_scan_terlambat_otomatis_status_telat()
    {
        $user = User::factory()->create(['role' => 'Kesiswaan']);
        $santri = Santriwati::create(['nama_lengkap' => 'Santri Telat', 'rfid_id' => '999', 'angkatan' => '2024']);
        
        Kegiatan::create([
            'nama_kegiatan' => 'SHUBUH',
            'jam' => '05:00:00',
            'tanggal' => now()->toDateString()
        ]);

        // Simulasikan jam 05:15 (Lewat dari toleransi 10 menit)
        Carbon::setTestNow(now()->setTime(5, 15));

        $this->actingAs($user)->post(route('presensi.check'), ['rfid' => '999']);

        $this->assertDatabaseHas('presensis', [
            'status' => 'TELAT'
        ]);
    }

    /** @test */
    public function sistem_mencegah_double_scan_di_kegiatan_yang_sama()
    {
        $user = User::factory()->create(['role' => 'Kesiswaan']);
        $santri = Santriwati::create(['nama_lengkap' => 'Santri Rajin', 'rfid_id' => '111', 'angkatan' => '2024']);
        
        $keg = Kegiatan::create([
            'nama_kegiatan' => 'TAHAJJUD',
            'jam' => '03:00:00',
            'tanggal' => now()->toDateString()
        ]);

        Carbon::setTestNow(now()->setTime(3, 5));

        // Scan Pertama
        $this->actingAs($user)->post(route('presensi.check'), ['rfid' => '111']);

        // Scan Kedua (Orang yang sama, kegiatan yang sama)
        $response = $this->actingAs($user)->post(route('presensi.check'), ['rfid' => '111']);

        $response->assertSessionHas('info', 'SANTRI SUDAH SCAN SEBELUMNYA!');
        $this->assertEquals(1, Presensi::count()); // Pastikan jumlah data di DB tetap 1, tidak jadi 2
    }
}