<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'avatar',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_kampus',
        'jurusan',
        'angkatan_akademik',
        'asal_daerah'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($avatar) => url('/storage/avatar/' . $avatar)
        );
    }
}
