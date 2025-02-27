<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TentangOrganisasiResource;
use App\Models\TentangOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TentangOrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = TentangOrganisasi::latest()->paginate(5);

        return new TentangOrganisasiResource(true, 'List data Tentang Organisasi', $organisasi);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'buku_saku' => 'required|file|mimes:pdf|max:10240',
            'pedoman_intern' => 'required|file|mimes:pdf|max:10240',
            'ringkasan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $logo = $request->file('logo');
        $logoName = time() . '_' . $logo->getClientOriginalName();
        $logoPath = $logo->storeAs('public/tentang-organisasi/logo', $logoName);

        $bukuSaku = $request->file('buku_saku');
        $bukuSakuName = time() . '_' . $bukuSaku->getClientOriginalName();
        $bukuSakuPath = $bukuSaku->storeAs('public/tentang-organisasi/buku-saku', $bukuSakuName);

        $pedomanIntern = $request->file('pedoman_intern');
        $pedomanInternName = time() . '_' . $pedomanIntern->getClientOriginalName();
        $pedomanInternPath = $pedomanIntern->storeAs('public/tentang-organisasi/pedoman-intern', $pedomanInternName);

        $organisasi = TentangOrganisasi::create([
            'logo' => $logoPath,
            'buku_saku' => $bukuSakuPath,
            'pedoman_intern' => $pedomanInternPath,
            'ringkasan' => $request->ringkasan
        ]);

        if ($organisasi) {
            return new TentangOrganisasiResource(true, 'Data Tentang Organisasi berhasil disimpan!', $organisasi);
        }

        return new TentangOrganisasiResource(false, 'Data Tentang Organisasi gagal disimpan!', null);
    }

    public function destroy($id)
    {
        $organisasi = TentangOrganisasi::findOrFail($id);

        if (Storage::exists($organisasi->logo)) {
            Storage::delete($organisasi->logo);
        }

        if (Storage::exists($organisasi->buku_saku)) {
            Storage::delete($organisasi->buku_saku);
        }

        if (Storage::exists($organisasi->pedoman_intern)) {
            Storage::delete($organisasi->pedoman_intern);
        }

        if ($organisasi->delete()) {
            return new TentangOrganisasiResource(true, 'Data Tentang Organisasi berhasil dihapus!', null);
        }

        return new TentangOrganisasiResource(false, 'Data Tentang Organisasi gagal dihapus!', null);
    }
}
