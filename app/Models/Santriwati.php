<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatabl;
class Santriwati extends Model
{
    protected $fillable = [
        'nama_lengkap', 'username', 'password', 'kelas', 'angkatan', 'rfid_id'
    ];

    // Menghubungkan ke data absen
    protected $hidden = [
        'password',
    ];
    // Menghubungkan ke data poin kedisiplinan
    public function presensis(): HasMany { return $this->hasMany(Presensi::class); }
    public function penilaians(): HasMany { return $this->hasMany(Penilaian::class); }
}
