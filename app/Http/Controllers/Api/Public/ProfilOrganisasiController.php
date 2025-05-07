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
        $profilOrganisasi = ProfilOrganisasi::oldest()->get();

        return new ProfilOrganisasiResource(true, 'List Data Profil Organisasi', $profilOrganisasi);
    }
}
