<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GaleriResource;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::when(request()->search, function ($galeri) {
            $galeri = $galeri->where('keterangan', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $galeri->appends(['search' => request()->search]);

        return new GaleriResource(true, 'List data Galeri', $galeri);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'gambar' => 'required|mimes:jpeg,png,jpg|max:5120',
            'keterangan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('public/galeri', $image->hashName());

        $galeri = Galeri::create([
            'gambar' => $image->hashName(),
            'keterangan' => $request->keterangan,
        ]);

        if ($galeri) {
            return new GaleriResource(true, 'Data Galeri berhasil disimpan!', $galeri);
        }

        return new GaleriResource(false, 'Data galeri gagal disimpan!', null);
    }

    public function destroy(Galeri $galeri)
    {
        Storage::disk('local')->delete('public/galeri/' . basename($galeri->gambar));

        if ($galeri->delete()) {
            return new GaleriResource(true, 'Data Galeri berhasil dihapus!', null);
        }

        return new GaleriResource(false, 'Data Galeri gagal dihapus!', null);
    }
}
