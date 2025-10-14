<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilOrganisasiResource;
use App\Models\ProfilOrganisasi;
use Illuminate\Http\Request;

class ProfilOrganisasiController extends Controller
{
    public function index()
    {
        $profilOrganisasi = ProfilOrganisasi::latest()->first();

        if ($profilOrganisasi) {
            $profilOrganisasi->logo_url = asset('storage/profil-organisasi/logo/' . $profilOrganisasi->logo);
            $profilOrganisasi->buku_saku_url = asset('storage/profil-organisasi/buku-saku/' . $profilOrganisasi->buku_saku);
            $profilOrganisasi->pedoman_intern_url = asset('storage/profil-organisasi/pedoman-intern/' . $profilOrganisasi->pedoman_intern);

            return new ProfilOrganisasiResource(true, 'Data Profil Organisasi', $profilOrganisasi);
        }

        return new ProfilOrganisasiResource(false, 'Data Profil Organisasi tidak ditemukan', null);
    }
}
