<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\KegiatanResource;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::with('user', 'kategori')->latest()->paginate(10);

        return new KegiatanResource(true, 'List data Kegiatan', $kegiatans);
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
