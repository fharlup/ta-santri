<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = ['santriwati_id', 'kegiatan_id', 'waktu_scan', 'status'];

    // Mengambil data santri dari sebuah data absen
    public function santriwati() {
        return $this->belongsTo(Santriwati::class);
    }

    // Mengambil data kegiatan dari sebuah data absen
    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class);
    }
}
