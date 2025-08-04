<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Bidang;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\KegiatanView;
use App\Models\ProgramKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $kegiatan_views = KegiatanView::select([
            //count id
            DB::raw('count(id) as `count`'),

            //get day from created at
            DB::raw('DATE(created_at) as day')

            //group by "day"
        ])->groupBy('day')

            //get data 30 days with carbon
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if (count($kegiatan_views)) {
            foreach ($kegiatan_views as $result) {
                $count[]   = (int) $result->count;
                $day[]     = $result->day;
            }
        } else {
            $count[] = "";
            $day[]  = "";
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kategori' => $kategori,
                'kegiatan' => $kegiatan,
                'bidang' => $bidang,
                'program_kerja' => $programKerja,
                'anggota' => $anggota,
                'kegiatan_views' => [
                    'count' => $count,
                    'days'   => $day
                ]
            ]
        ]);
    }
}
