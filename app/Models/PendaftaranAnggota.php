<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranAnggota extends Model
{
    //
    protected $fillable = [
        'user_id',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_kampus',
        'jurusan',
        'angkatan_akademik',
        'asal_daerah',
        'status'
    ];
}
