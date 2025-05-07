<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'avatar',
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($avatar) => url('/storage/pengurus/', $avatar)
        );
    }
}
