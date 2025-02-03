<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KegiatanResource;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'judul' => 'required',
            'konten' => 'required',
            'kategori_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/kegiatan', $image->hashName());

        $kegiatan = Kegiatan::create([
            'image' => $image->hashName(),
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul, '-'),
            'kategori_id' => $request->kategori_id,
            'user_id' => auth()->guard('api')->user()->id,
            'konten' => $request->konten
        ]);

        if ($kegiatan) {
            return new KegiatanResource(true, 'Data kegiatan berhasil ditambahkan', $kegiatan);
        }

        return new KegiatanResource(false, 'Data kegiatan gagal disimpan', null);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required' . $kegiatan->id,
            'kategori_id' => 'required',
            'konten' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('image')) {
            Storage::disk('local')->delete('public/kegiatan/' . basename($kegiatan->image));

            $image = $request->file('image');
            $image->storeAs('public/kegiatan', $image->hashName());

            $kegiatan->update([
                'image' => $image->hashName(),
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
            return new KegiatanResource(true, 'Data kegiatan berhasil diperbarui', $kegiatan);
        }

        return new KegiatanResource(false, 'Data kegiatan gagal diperbarui', null);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        Storage::disk('local')->delete('public/kegiatan/' . basename($kegiatan->image));

        if ($kegiatan->delete()) {
            return new KegiatanResource(true, 'Data kegiatan berhasil dihapus', null);
        }

        return new KegiatanResource(false, 'Data kegiatan gagal dihapus', null);
    }
}
