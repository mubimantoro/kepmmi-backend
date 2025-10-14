<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekrutmenAnggotaResource;
use App\Models\RekrutmenAnggota;
use Illuminate\Http\Request;

class RiwayatPendaftaranAnggotaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->guard('api')->user();

        $riwayat = RekrutmenAnggota::with('periodeRekrutmenAnggota')
            ->where('user_id', $user->id)
            ->get();

        return new RekrutmenAnggotaResource(true, 'Data Riwayat Pendaftaran Anggota', $riwayat);
    }
}
