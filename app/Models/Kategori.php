<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama',
        'slug',
    ];

    public function user()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
