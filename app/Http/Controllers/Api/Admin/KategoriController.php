<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::when(request()->search, function ($kategori) {
            $kategori = $kategori->where('nama', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $kategori->appends(['search' => request()->search]);

        return new KategoriResource(true, 'List Data Kategori Kegiatan', $kategori);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = Kategori::create([
            'nama' => $request->nama
        ]);

        if ($kategori) {
            return new KategoriResource(true, 'Data kategori kegiatan berhasil disimpan', $kategori);
        }

        return new KategoriResource(false, 'Data kategori kegiatan gagal disimpan', null);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:kategoris,nama'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama, '-')
        ]);

        if ($kategori) {
            return new KategoriResource(true, 'Data kategori kegiatan berhasil diperbarui', $kategori);
        }

        return new KategoriResource(false, 'Data kategori kegiatan gagal diperbarui', null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->delete()) {
            return new KategoriResource(true, 'Data kategori kegiatan berhasil dihapus', null);
        }

        return new KategoriResource(false, 'Data kategori kegiatan gagal dihapus', null);
    }

    public function all()
    {
        $kategori = Kategori::latest()->get();
        return new KategoriResource(true, 'List data kategori kegiatan', $kategori);
    }
}
