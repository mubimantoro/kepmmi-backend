<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Bidang;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $kategori = Kategori::count();
        $kegiatan = Kegiatan::count();
        $anggota = Anggota::count();
        $bidang = Bidang::count();
        $programKerja = ProgramKerja::count();

        return response()->json([
            'success' => true,
            'data' => [
                'kategori_kegiatan' => $kategori,
                'kegiatan' => $kegiatan,
                'bidang' => $bidang,
                'program_kerja' => $programKerja,
                'anggota' => $anggota,
            ]
        ]);
    }
}
