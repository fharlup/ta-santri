<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santriwati extends Model
{
    protected $fillable = ['nim', 'nama_lengkap', 'kelas', 'rfid_id'];

    // Menghubungkan ke data absen
    public function presensis() {
        return $this->hasMany(Presensi::class);
    }

    // Menghubungkan ke data poin kedisiplinan
    public function penilaians() {
        return $this->hasMany(Penilaian::class);
    }
}
