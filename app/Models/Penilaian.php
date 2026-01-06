<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
      'santriwati_id', 'tanggal', 'angkatan',
    'disiplin', 'k3', 'tanggung_jawab', 
    'inisiatif_kreatifitas', // Pakai 'f' sesuai migration Anda
    'adab', 'berterate',
    'integritas_kesabaran', 'integritas_produktif', 
    'integritas_mandiri', 'integritas_optimis', 'integritas_kejujuran',
    'deskripsi'    
    ];
        protected $casts = [
        'tanggal' => 'date',
    ];
    public function santriwati() {
        return $this->belongsTo(Santriwati::class);
    }
}
