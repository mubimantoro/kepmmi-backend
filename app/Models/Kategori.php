<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    //

    protected $fillable = [
        'name',
        'slug'
    ];

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
