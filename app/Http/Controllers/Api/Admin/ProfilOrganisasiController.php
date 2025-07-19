<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilOrganisasiResource;
use App\Models\ProfilOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilOrganisasiController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware(['permission:profil_organisasi.index'], only: ['index']),
            new Middleware(['permission:profil_organisasi.create'], only: ['store']),
            new Middleware(['permission:profil_organisasi.edit'], only: ['update']),
            new Middleware(['permission:profil_organisasi.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $profilOrganisasi = ProfilOrganisasi::latest()->paginate(5);

        return new ProfilOrganisasiResource(true, 'List data Profil Organisasi', $profilOrganisasi);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:10240',
            'buku_saku' => 'required|file|mimes:pdf|max:10240',
            'pedoman_intern' => 'required|file|mimes:pdf|max:10240',
            'ringkasan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $logo = $request->file('logo');
        $logo->storeAs('profil-organisasi/logo', $logo->hashName(), 'public');

        $bukuSaku = $request->file('buku_saku');
        $bukuSaku->storeAs('profil-organisasi/buku-saku', $bukuSaku->hashName(), 'public');

        $pedomanIntern = $request->file('pedoman_intern');
        $pedomanIntern->storeAs('profil-organisasi/pedoman-intern', $pedomanIntern->hashName(), 'public');

        $profilOrganisasi = ProfilOrganisasi::create([
            'logo' => $logo->hashName(),
            'buku_saku' => $bukuSaku->hashName(),
            'pedoman_intern' => $pedomanIntern->hashName(),
            'ringkasan' => $request->ringkasan
        ]);

        if ($profilOrganisasi) {
            return new ProfilOrganisasiResource(true, 'Data Profil Organisasi berhasil disimpan!', $profilOrganisasi);
        }

        return new ProfilOrganisasiResource(false, 'Data Profil Organisasi gagal disimpan!', null);
    }

    public function show($id)
    {
        $profilOrganisasi = ProfilOrganisasi::whereId($id)->first();

        if ($profilOrganisasi) {
            return new ProfilOrganisasiResource(true, 'Detail data Profil Organisasi!', $profilOrganisasi);
        }

        return new ProfilOrganisasiResource(false, 'Detail data Profil tidak ditemukan!', null);
    }

    public function update(Request $request, ProfilOrganisasi $profilOrganisasi)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:10240',
            'buku_saku' => 'nullable|file|mimes:pdf|max:10240',
            'pedoman_intern' => "nullable|file|mimes:pdf|max:10240",
            'ringkasan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('logo')) {
            Storage::disk('public')->delete('profil-organisasi/logo/' . basename($profilOrganisasi->logo));

            $logo = $request->file('logo');
            $logo->storeAs('profil-organisasi/logo', $logo->hashName(), 'public');

            $profilOrganisasi->update([
                'logo' => $logo->hashName(),
                'ringkasan' => $request->ringkasan
            ]);
        }

        if ($request->file('buku_saku')) {
            Storage::disk('public')->delete('profil-organisasi/buku-saku/' . basename($profilOrganisasi->buku_saku));

            $bukuSaku = $request->file('buku_saku');
            $bukuSaku->storeAs('profil-organisasi/buku-saku', $bukuSaku->hashName(), 'public');

            $profilOrganisasi->update([
                'buku_saku' => $bukuSaku->hashName(),
                'ringkasan' => $request->ringkasan
            ]);
        }

        if ($request->file('pedoman_intern')) {
            Storage::disk('public')->delete('profil-organisasi/pedoman-intern/' . basename($profilOrganisasi->pedoman_intern));

            $pedomanIntern = $request->file('pedoman_intern');
            $pedomanIntern->storeAs('profil-organisasi/pedoman-intern', $pedomanIntern->hashName(), 'public');


            $profilOrganisasi->update([
                'pedoman_intern' => $pedomanIntern->hashName(),
                'ringkasan' => $request->ringkasan
            ]);
        }

        $profilOrganisasi->update([
            'ringkasan' => $request->ringkasan
        ]);

        if ($profilOrganisasi) {
            return new ProfilOrganisasiResource(true, 'Data Profil Organisasi berhasil diupdate!', $profilOrganisasi);
        }

        return new ProfilOrganisasiResource(false, 'Data Profil Organisasi gagal diupdate!', null);
    }

    public function destroy(ProfilOrganisasi $profilOrganisasi)
    {

        Storage::disk('public')->delete('profil-organisasi/logo', basename($profilOrganisasi->logo));
        Storage::disk('public')->delete('profil-organisasi/buku-saku', basename($profilOrganisasi->buku_saku));
        Storage::disk('public')->delete('profil-organisasi/pedoman-intern', basename($profilOrganisasi->pedoman_intern));

        if ($profilOrganisasi->delete()) {
            return new ProfilOrganisasiResource(true, 'Data Profil Organisasi berhasil dihapus!', null);
        }

        return new ProfilOrganisasiResource(false, 'Data Profil Organisasi gagal dihapus!', null);
    }
}
