<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    //
    protected $fillable = [
        'nama_bidang',
        'tugas_bidang',
    ];

    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
