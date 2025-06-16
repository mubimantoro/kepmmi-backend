<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(['permission:kategori.index'], only: ['index', 'all']),
            new Middleware(['permission:kategori.create'], only: ['store']),
            new Middleware(['permission:kategori.edit'], only: ['update']),
            new Middleware(['permission:kategori.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $kategori = Kategori::when(request()->search, function ($kategori) {
            $kategori = $kategori->where('nama', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $kategori->appends(['search' => request()->search]);

        return new KategoriResource(true, 'List data Kategori Kegiatan', $kategori);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:kategoris'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama, '-'),
        ]);

        if ($kategori) {
            return new KategoriResource(true, 'Data Kategori berhasil disimpan!', $kategori);
        }

        return new KategoriResource(false, 'Data Kategori gagal disimpan!', null);
    }

    public function show($id)
    {
        $kategori = Kategori::whereId($id)->first();

        if ($kategori) {
            return new KategoriResource(true, 'Detail data Kategori!', $kategori);
        }

        return new KategoriResource(false, 'Detail data Kategori tidak ditemukan!', null);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:kategoris,nama,' . $kategori->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama, '-'),
        ]);

        if ($kategori) {
            return new KategoriResource(true, 'Data Kategori berhasil diupdate!', $kategori);
        }

        return new KategoriResource(false, 'Data Kategori gagal diupdate!', null);
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->delete()) {
            return new KategoriResource(true, 'Data Kategori berhasil dihapus!', null);
        }

        return new KategoriResource(false, 'Data Kategori gagal dihapus!', null);
    }

    public function all()
    {
        $kategori = Kategori::latest()->get();
        return new KategoriResource(true, 'List data Kategori', $kategori);
    }
}
