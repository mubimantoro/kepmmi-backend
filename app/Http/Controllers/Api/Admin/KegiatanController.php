<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KegiatanResource;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KegiatanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:kegiatan.index'], only: ['index']),
            new Middleware(['permission:kegiatan.create'], only: ['store']),
            new Middleware(['permission:kegiatan.edit'], only: ['update']),
            new Middleware(['permission:kegiatan.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $kegiatans = Kegiatan::with('user', 'kategori')->when(request()->search, function ($kegiatans) {
            $kegiatans = $kegiatans->where('judul', 'like', '%' . request()->search . '%');
        })->where('user_id', auth()->user()->id)->latest()->paginate(5);

        $kegiatans->appends(['search' => request()->search]);

        return new KegiatanResource(true, 'List data Kegiatan', $kegiatans);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:10240',
            'judul' => 'required|unique:kegiatans',
            'konten' => 'required',
            'kategori_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('kegiatan', $image->hashName(), 'public');

        $kegiatan = Kegiatan::create([
            'gambar' => $image->hashName(),
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul, '-'),
            'kategori_id' => $request->kategori_id,
            'user_id' => auth()->guard('api')->user()->id,
            'konten' => $request->konten
        ]);

        if ($kegiatan) {
            return new KegiatanResource(true, 'Data Kegiatan berhasil disimpan!', $kegiatan);
        }

        return new KegiatanResource(false, 'Data Kegiatan gagal disimpan!', null);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with('kategori')->whereId($id)->first();

        if ($kegiatan) {
            return new KegiatanResource(true, 'Detail data Kegiatan!', $kegiatan);
        }

        return new KegiatanResource(false, 'Detail data Kegiatan tidak ditemukan!', null);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'judul' => 'required|unique:kegiatans,judul,' . $kegiatan->id,
            'kategori_id' => 'required',
            'konten' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('gambar')) {

            Storage::disk('public')->delete('kegiatan/' . basename($kegiatan->gambar));

            $image = $request->file('gambar');
            $image->storeAs('kegiatan', $image->hashName(), 'public');

            $kegiatan->update([
                'gambar' => $image->hashName(),
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul, '-'),
                'kategori_id' => $request->kategori_id,
                'user_id' => auth()->guard('api')->user()->id,
                'konten' => $request->konten
            ]);
        }

        $kegiatan->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul, '-'),
            'kategori_id' => $request->kategori_id,
            'user_id' => auth()->guard('api')->user()->id,
            'konten' => $request->konten
        ]);

        if ($kegiatan) {
            return new KegiatanResource(true, 'Data Kegiatan berhasil diupdate!', $kegiatan);
        }

        return new KegiatanResource(false, 'Data Kegiatan gagal diupdate!', null);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        Storage::disk('public')->delete('kegiatan/' . basename($kegiatan->gambar));

        if ($kegiatan->delete()) {
            return new KegiatanResource(true, 'Data Kegiatan berhasil dihapus!', null);
        }

        return new KegiatanResource(false, 'Data Kegiatan gagal dihapus', null);
    }
}
