<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeRekrutmenAnggota extends Model
{
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => 'boolean'
    ];

    public function rekrutmenAnggotas()
    {
        return $this->hasMany(RekrutmenAnggota::class);
    }
}
