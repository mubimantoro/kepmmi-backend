<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnggotaResource;
use App\Models\User;
use Illuminate\Http\Request;

class AnggotaContoller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $anggota = User::with([
            'anggota',
            'anggota.jenisAnggota'
        ])
            ->where('id', $user->id)
            ->first();

        return new AnggotaResource(true, 'Detail data Anggota', $anggota);
    }
}
