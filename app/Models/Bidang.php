<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    //
    protected $fillable = [
        'nama',
    ];

    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
