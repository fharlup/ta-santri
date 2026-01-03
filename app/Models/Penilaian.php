<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = ['santriwati_id', 'muatan_karakter', 'skor', 'deskripsi'];

    public function santriwati() {
        return $this->belongsTo(Santriwati::class);
    }
}
