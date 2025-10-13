<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_kampus',
        'jurusan',
        'angkatan_akademik',
        'asal_daerah',
        'no_hp',
        'nama_wali',
        'no_hp_wali'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
