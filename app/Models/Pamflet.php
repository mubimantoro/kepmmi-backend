<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Pamflet extends Model
{
    protected $fillable = [
        'gambar',
        'caption'
    ];

    protected function gambar(): Attribute
    {
        return  Attribute::make(
            get: fn($gambar) => url('/storage/pamflet/' . $gambar)
        );
    }
}
