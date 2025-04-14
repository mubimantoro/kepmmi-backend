<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $category = Kategori::when(request()->search, function ($category) {
            $category = $category->where('nama', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $category->appends(['search' => request()->search]);

        return new KategoriResource(true, 'List data Kategori Kegiatan', $category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama, '-'),
        ]);

        if ($category) {
            return new KategoriResource(true, 'Data Kategori berhasil disimpan!', $category);
        }

        return new KategoriResource(false, 'Data Kategori gagal disimpan!', null);
    }

    public function destroy(Kategori $category)
    {
        if ($category->delete()) {
            return new KategoriResource(true, 'Data Kategori berhasil dihapus!', null);
        }

        return new KategoriResource(false, 'Data Kategori gagal dihapus!', null);
    }

    public function all()
    {
        $category = Kategori::latest()->get();
        return new KategoriResource(true, 'List data Kategori', $category);
    }
}
