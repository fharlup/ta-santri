<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test; // Wajib untuk standar baru

class SiDisiplinTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function tc01_login_berhasil_dan_redirect_dashboard()
    {
        User::create(['username' => 'admin', 'password' => bcrypt('123'), 'role' => 'Kesiswaan']);

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => '123'
        ]);

        $response->assertRedirect('/kesiswaan/dashboard');
        $this->assertAuthenticated();
    }

    #[Test]
    public function tc02_login_gagal_menampilkan_error_session()
    {
        User::create(['username' => 'admin', 'password' => bcrypt('123'), 'role' => 'Kesiswaan']);

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'salah'
        ]);

        // Memastikan ada pesan error di session (FR-01)
        $response->assertSessionHasErrors('username');
    }

    #[Test]
    public function tc03_keamanan_hak_akses_role()
    {
        $santri = User::create(['username' => 'santri', 'password' => '123', 'role' => 'Santriwati']);
        $this->actingAs($santri)->get('/kesiswaan/dashboard')->assertStatus(403);
    }

    #[Test]
    public function tc04_tc05_tc06_crud_master_dan_kegiatan()
    {
        $admin = User::create(['username' => 'admin', 'password' => '123', 'role' => 'Kesiswaan']);
        $this->actingAs($admin);

        $this->post('/kesiswaan/santri', ['nim' => '001', 'nama_lengkap' => 'Siti', 'kelas' => '12A', 'rfid_id' => 'RF01']);
        $this->assertDatabaseHas('santriwatis', ['nama_lengkap' => 'Siti']);

        $this->post('/kesiswaan/kegiatan', ['nama_kegiatan' => 'Tahfidz', 'ustadzah_pendamping' => 'Usth. Sarah', 'waktu_mulai' => now()]);
        $this->assertDatabaseHas('kegiatans', ['nama_kegiatan' => 'Tahfidz']);
    }

    #[Test]
    public function tc07_sampai_tc10_rfid_dan_logika_terlambat()
    {
        $komdis = User::create(['username' => 'komdis', 'password' => '123', 'role' => 'KOMDIS']);
        $santri = Santriwati::create(['nim' => '001', 'nama_lengkap' => 'Aisyah', 'kelas' => '12A', 'rfid_id' => 'RFID_ABC']);
        $kegiatan = Kegiatan::create(['nama_kegiatan' => 'Subuh', 'ustadzah_pendamping' => 'Usth. A', 'waktu_mulai' => Carbon::now()->setTime(4, 30)]);

        // Scan Tepat Waktu
        Carbon::setTestNow(Carbon::now()->setTime(4, 20));
        $this->actingAs($komdis)->post('/komdis/scan', ['rfid_string' => 'RFID_ABC', 'kegiatan_id' => $kegiatan->id]);
        $this->assertDatabaseHas('presensis', ['status' => 'Hadir']);

        // Scan Terlambat
        Carbon::setTestNow(Carbon::now()->setTime(4, 40));
        $this->actingAs($komdis)->post('/komdis/scan', ['rfid_string' => 'RFID_ABC', 'kegiatan_id' => $kegiatan->id]);
        $this->assertDatabaseHas('presensis', ['status' => 'Terlambat']);
    }

    #[Test]
    public function tc17_log_aktivitas_tercatat()
    {
        $admin = User::create(['username' => 'admin', 'password' => '123', 'role' => 'Kesiswaan']);
        $this->actingAs($admin);

        $santri = Santriwati::create(['nim' => '001', 'nama_lengkap' => 'Zahra', 'kelas' => '12A', 'rfid_id' => 'RF01']);
        $this->delete("/kesiswaan/santri/{$santri->id}");

        $this->assertDatabaseHas('logs', ['aktivitas' => 'Menghapus data santri: Zahra']);
    }
}