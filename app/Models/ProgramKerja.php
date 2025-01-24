<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    //
    protected $fillable = [
        'bidang_id',
        'nama',
        'status',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
