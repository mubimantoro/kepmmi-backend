<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'kategori_id',
        'user_id',
        'judul',
        'slug',
        'gambar',
        'konten'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(KegiatanView::class);
    }

    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn($gambar) => url('/storage/kegiatan/' .  $gambar)
        );
    }
}
