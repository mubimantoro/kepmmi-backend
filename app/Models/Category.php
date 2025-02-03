<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nama',
        'slug'
    ];

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
