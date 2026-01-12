<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Penilaian extends Model
{
    protected $fillable = [
      'santriwati_id',
    'user_id', // PASTIKAN INI ADA
    'tanggal', 
    'angkatan',
    'disiplin', 
    'k3', 
    'tanggung_jawab', 
    'inisiatif_kreatifitas',
    'adab', 
    'berterate',
    'integritas_kesabaran', 
    'integritas_produktif', 
    'integritas_mandiri', 
    'integritas_optimis', 
    'integritas_kejujuran',
    'deskripsi'
    ];
        protected $casts = [
        'tanggal' => 'date',
    ];
    /**
     * Relasi ke model Santriwati
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Santriwati
     */
    public function santriwati(): BelongsTo
    {
        return $this->belongsTo(Santriwati::class, 'santriwati_id');
    }
    
}
