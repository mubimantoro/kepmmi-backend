<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StrukturOrganisasiResource;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $strukturOrganisasi = StrukturOrganisasi::latest()->paginate(5);

        return new StrukturOrganisasiResource(true, 'List data Struktur Pengurus Organisasi', $strukturOrganisasi);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('struktur-organisasi', $image->hashName(), 'public');

        $strukturOrganisasi = StrukturOrganisasi::create([
            'gambar' => $image->hashName()
        ]);

        if ($strukturOrganisasi) {
            return new StrukturOrganisasiResource(true, 'Data Struktur Pengurus Organisasi berhasil disimpan!', $strukturOrganisasi);
        }

        return new StrukturOrganisasiResource(false, 'Data Struktur Pengurus Organisasi gagal disimpan!', null);
    }

    public function destroy(StrukturOrganisasi $strukturOrganisasi)
    {

        Storage::disk('public')->delete('struktur-organisasi/' . basename($strukturOrganisasi->gambar));

        if ($strukturOrganisasi->delete()) {
            return new StrukturOrganisasiResource(true, 'Data Struktur Pengurus Organisasi berhasil dihapus!', null);
        }

        return new StrukturOrganisasiResource(false, 'Data Struktur Pengurus Organisasi gagal dihapus!', null);
    }
}
