<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProfilOrganisasi extends Model
{
    protected $fillable = [
        'logo',
        'buku_saku',
        'pedoman_intern',
        'ringkasan'
    ];

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($logo) => url('/storage/profil-organisasi/logo' . $logo)
        );
    }

    protected function bukuSaku(): Attribute
    {
        return Attribute::make(
            get: fn($bukuSaku) => url('/storage/profil-organisasi/buku-saku' . $bukuSaku)
        );
    }

    protected function pedomanIntern(): Attribute
    {
        return Attribute::make(
            get: fn($pedomanIntern) => url('/storage/profil-organisasi/pedoman-intern', $pedomanIntern)
        );
    }
}
