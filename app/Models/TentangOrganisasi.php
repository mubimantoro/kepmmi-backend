<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentangOrganisasi extends Model
{
    protected $fillable = [
        'logo',
        'buku_saku',
        'pedoman_intern',
        'ringkasan'
    ];
}
