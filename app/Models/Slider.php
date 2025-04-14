<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'gambar'
    ];

    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn($gambar) => url('/storage/sliders/' . $gambar),
        );
    }
}
