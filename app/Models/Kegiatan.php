<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{

    protected $fillable = [
            'nama_kegiatan', // Contoh: 'Tahajjud', 'KOMDIS' [cite: 26]
        'jam',           // Waktu mulai
        'tanggal',       // Tanggal pelaksanaan 
        'angkatan',      // Angkatan yang wajib ikut 
        'ustadzah_1',    // Pendamping 1 
        'ustadzah_2',    // Pendamping 2 
        'ustadzah_3'     //

    ];
    public function presensis() {
        return $this->hasMany(Presensi::class);
    }
    protected $casts = [
        'tanggal' => 'date',
    ];
}
