<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekrutmenAnggota extends Model
{
    protected $fillable = [
        'user_id',
        'periode_rekrutmen_anggota_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periodeRekrutmenAnggota()
    {
        return $this->belongsTo(PeriodeRekrutmenAnggota::class);
    }
}
