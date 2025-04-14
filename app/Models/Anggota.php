<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_anggota_id'
    ];

    public function jenisAnggota()
    {
        return $this->belongsTo(JenisAnggota::class);
    }
}
