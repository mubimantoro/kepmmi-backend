<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\KegiatanResource;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with('user', 'kategori')->withCount('views')->latest()->paginate(10);

        return new KegiatanResource(true, 'List data Kegiatan', $kegiatan);
    }

    public function show($slug)
    {
        $kegiatan = Kegiatan::with('user', 'kategori')->withCount('views')->where('slug', $slug)->first();

        if ($kegiatan) {
            //return with Api Resource
            return new KegiatanResource(true, 'Detail data Kegiatan', $kegiatan);
        }

        //return with Api Resource
        return new KegiatanResource(false, 'Detail data Kegiatan tidak ditemukan!', null);
    }


    public function homePage()
    {
        $kegiatans = Kegiatan::with('user', 'kategori')->latest()->take(6)->get();

        return new KegiatanResource(true, 'List data Kegiatan HomePage', $kegiatans);
    }

    public function storeImageKegiatan(Request $request)
    {
        $image = $request->file('gambar');
        $image->storeAs('gambar-kegiatan', $image->hashName(), 'public');

        return response()->json([
            'url' => asset('storage/gambar-kegiatan/' . $image->hashName())
        ]);
    }
}
