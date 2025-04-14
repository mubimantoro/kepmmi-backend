<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StrukturPengurus extends Model
{
    protected $fillable = [
        'gambar'
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/struktur-pengurus', $image),
        );
    }
}
