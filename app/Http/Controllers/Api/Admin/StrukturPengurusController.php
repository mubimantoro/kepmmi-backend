<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StrukturPengurusResource;
use App\Models\StrukturPengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StrukturPengurusController extends Controller
{
    public function index()
    {
        $strukturPengurus = StrukturPengurus::latest()->paginate(5);

        return new StrukturPengurusResource(true, 'List data Struktur Pengurus Organisasi', $strukturPengurus);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('struktur-pengurus', $image->hashName(), 'public');

        $strukturPengurus = StrukturPengurus::create([
            'gambar' => $image->hashName()
        ]);

        if ($strukturPengurus) {
            return new StrukturPengurusResource(true, 'Data Struktur Pengurus Organisasi berhasil disimpan!', $strukturPengurus);
        }

        return new StrukturPengurusResource(false, 'Data Struktur Pengurus Organisasi gagal disimpan!', null);
    }

    public function destroy(StrukturPengurus $strukturPengurus)
    {
        Storage::disk('local')->delete('public/struktur-pengurus', basename($strukturPengurus->gambar));

        if ($strukturPengurus->delete()) {
            return new StrukturPengurusResource(true, 'Data Struktur Pengurus Organisasi berhasil dihapus!', null);
        }

        return new StrukturPengurusResource(false, 'Data Struktur Pengurus Organisasi gagal dihapus!', null);
    }
}
