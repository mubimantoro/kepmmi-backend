<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodeRekrutmenAnggotaResource;
use App\Models\PeriodeRekrutmenAnggota;
use Illuminate\Http\Request;

class PeriodeRekrutmenController extends Controller
{
    public function index()
    {
        $periodes = PeriodeRekrutmenAnggota
            ::where('status', true)
            ->first();

        return new PeriodeRekrutmenAnggotaResource(true, 'List data Periode Rekrutmen Anggota', $periodes);
    }
}
