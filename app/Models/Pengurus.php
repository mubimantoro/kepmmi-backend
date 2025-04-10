<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $fillable = [
        'user_id',
        'jabatan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profil()
    {
        return $this->hasOneThrough(
            Profil::class,
            User::class
        );
    }
}
