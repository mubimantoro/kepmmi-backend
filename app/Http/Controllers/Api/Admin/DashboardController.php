<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $anggota = Anggota::count();
        $kegiatan = Kegiatan::count();

        return response()->json([
            'success' => true,
            'data' => [
                'anggota' => $anggota,
                'kegiatan' => $kegiatan,
            ]
        ]);
    }
}
