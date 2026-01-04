<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = [
        'santriwati_id', 
        'kegiatan_id', 
        'waktu_scan', 
        'status', 
        'keterangan'
    ];

    // Tambahkan bagian ini agar ->format() bisa digunakan
    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function santriwati()
    {
        return $this->belongsTo(Santriwati::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}