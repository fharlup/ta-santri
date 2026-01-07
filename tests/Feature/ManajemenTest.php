<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Santriwati;
use App\Models\Kegiatan;
use App\Models\Penilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManajemenTest extends TestCase
{
    use RefreshDatabase;

    // --- TESTING USER MANAGEMENT ---

    /** @test */
    public function kesiswaan_bisa_menambah_staf_baru()
    {
        $admin = User::factory()->create(['role' => 'Kesiswaan']);

        $response = $this->actingAs($admin)->post(route('user.store'), [
            'nama_lengkap' => 'Ustadzah Maryam',
            'email' => 'maryam@tunasquran.com',
            'password' => 'password123',
            'role' => 'Wali Kelas'
        ]);

        $response->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', ['email' => 'maryam@tunasquran.com']);
    }

    /** @test */
    public function kesiswaan_bisa_mengedit_data_staf()
    {
        $admin = User::factory()->create(['role' => 'Kesiswaan']);
        $staf = User::factory()->create(['nama_lengkap' => 'Staf Lama']);

        $response = $this->actingAs($admin)->put(route('user.update', $staf->id), [
            'nama_lengkap' => 'Staf Baru',
            'email' => $staf->email,
            'role' => 'Komdis'
        ]);

        $this->assertDatabaseHas('users', ['nama_lengkap' => 'Staf Baru']);
    }

    // --- TESTING KEGIATAN MANAGEMENT ---

    /** @test */
    public function kesiswaan_bisa_membuat_jadwal_kegiatan()
    {
        $admin = User::factory()->create(['role' => 'Kesiswaan']);

        $response = $this->actingAs($admin)->post(route('kegiatan.store'), [
            'nama_kegiatan' => 'TAHSIN PAGI',
            'jam' => '06:00:00',
            'tanggal' => '2026-01-10',
            'angkatan' => '2024'
        ]);

        $this->assertDatabaseHas('kegiatans', ['nama_kegiatan' => 'TAHSIN PAGI']);
    }

    // --- TESTING PENILAIAN (KEDISIPLINAN) ---

    /** @test */
    public function staf_bisa_input_pelanggaran_santri()
    {
        $admin = User::factory()->create(['role' => 'Kesiswaan']);
        $santri = Santriwati::create(['nama_lengkap' => 'Naila', 'rfid_id' => '123', 'angkatan' => '2024']);

        $response = $this->actingAs($admin)->post(route('penilaian.store'), [
            'santriwati_id' => $santri->id,
            'kategori' => 'Kerapian',
            'poin' => 5,
            'keterangan' => 'Tidak memakai kerudung sesuai aturan'
        ]);

        $this->assertDatabaseHas('penilaians', [
            'santriwati_id' => $santri->id,
            'poin' => 5
        ]);
    }
}