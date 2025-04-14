<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeRekrutmenAnggota extends Model
{
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    protected $casts = [
        // 'tanggal_mulai' => 'date',
        // 'tanggal_selesai' => 'date',
        'status' => 'boolean'
    ];

    public function rekrutmenAnggotas()
    {
        return $this->hasMany(RekrutmenAnggota::class);
    }
}
