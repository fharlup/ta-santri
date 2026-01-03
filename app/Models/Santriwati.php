<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatabl;
class Santriwati extends Model
{
    protected $fillable = [
        'nama_lengkap', 'nim', 'username', 'password', 'kelas', 'rfid_id'
    ];

    // Menghubungkan ke data absen
    public function presensis() {
        return $this->hasMany(Presensi::class);
    }
    protected $hidden = [
        'password',
    ];
    // Menghubungkan ke data poin kedisiplinan
    public function penilaians() {
        return $this->hasMany(Penilaian::class);
    }
}
