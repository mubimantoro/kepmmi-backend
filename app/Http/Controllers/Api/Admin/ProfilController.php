<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilResource;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfilController extends Controller
{
    public function index()
    {
        $profil = Profil::latest()->paginate(5);

        return new ProfilResource(true, 'List data Profil Organisasi', $profil);
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
        $logoPath = $logo->storeAs('public/profil/logo', $logoName);

        $bukuSaku = $request->file('buku_saku');
        $bukuSakuName = time() . '_' . $bukuSaku->getClientOriginalName();
        $bukuSakuPath = $bukuSaku->storeAs('public/profil/buku-saku', $bukuSakuName);

        $pedomanIntern = $request->file('pedoman_intern');
        $pedomanInternName = time() . '_' . $pedomanIntern->getClientOriginalName();
        $pedomanInternPath = $pedomanIntern->storeAs('public/profil/pedoman-intern', $pedomanInternName);

        $profil = Profil::create([
            'logo' => $logoPath,
            'buku_saku' => $bukuSakuPath,
            'pedoman_intern' => $pedomanInternPath,
            'ringkasan' => $request->ringkasan
        ]);

        if ($profil) {
            return new ProfilResource(true, 'Data Profil Organisasi berhasil disimpan!', $profil);
        }

        return new ProfilResource(false, 'Data Profil Organisasi gagal disimpan!', null);
    }

    public function show($id)
    {
        $profil = Profil::whereId($id)->first();

        if ($profil) {
            return new ProfilResource(true, 'Detail data Profil Organisasi!', $profil);
        }

        return new ProfilResource(false, 'Detail data Profil tidak ditemukan!', null);
    }

    public function update(Request $request, Profil $profil)
    {
        $validator = Validator::make($request->all(), [
            'ringkasan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('logo')) {
            Storage::disk('local')->delete('public/profil/logo/' . basename($profil->logo));

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('public/profil/logo', $logoName);

            $profil->update([
                'logo' => $logoPath,
                'ringkasan' => $request->ringkasan
            ]);
        }

        if ($request->file('buku_saku')) {
            Storage::disk('local')->delete('public/profil/buku-saku/' . basename($profil->buku_saku));

            $bukuSaku = $request->file('buku_saku');
            $bukuSakuName = time() . '_' . $bukuSaku->getClientOriginalName();
            $bukuSakuPath = $bukuSaku->storeAs('public/profil/buku-saku', $bukuSakuName);

            $profil->update([
                'buku_saku' => $bukuSakuPath,
                'ringkasan' => $request->ringkasan
            ]);
        }

        if ($request->file('pedoman_intern')) {
            Storage::disk('local')->delete('public/profil/pedoman-intern/' . basename($profil->pedoman_intern));

            $pedomanIntern = $request->file('pedoman_intern');
            $pedomanInternName = time() . '_' . $pedomanIntern->getClientOriginalName();
            $pedomanInternPath = $pedomanIntern->storeAs('public/profil/pedoman-intern', $pedomanInternName);

            $profil->update([
                'pedoman_intern' => $pedomanInternPath,
                'ringkasan' => $request->ringkasan
            ]);
        }

        $profil->update([
            'ringkasan' => $request->ringkasan
        ]);

        if ($profil) {
            return new ProfilResource(true, 'Data Profil Organisasi berhasil diupdate!', $profil);
        }

        return new ProfilResource(false, 'Data Profil Organisasi gagal diupdate!', null);
    }

    public function destroy(Profil $profil)
    {

        Storage::disk('local')->delete('public/profil/logo', basename($profil->logo));
        Storage::disk('local')->delete('public/profil/buku-saku', basename($profil->buku_saku));
        Storage::disk('local')->delete('public/profil/pedoman-intern', basename($profil->pedoman_intern));

        if ($profil->delete()) {
            return new ProfilResource(true, 'Data Profil Organisasi berhasil dihapus!', null);
        }

        return new ProfilResource(false, 'Data Profil Organisasi gagal dihapus!', null);
    }
}
