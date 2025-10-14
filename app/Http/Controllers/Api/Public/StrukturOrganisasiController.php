<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\StrukturOrganisasiResource;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $strukturOrganisasi = StrukturOrganisasi::latest()->first();

        if ($strukturOrganisasi) {
            return new StrukturOrganisasiResource(true, 'Data Struktur Pengurus Organisasi', $strukturOrganisasi);
        }

        return new StrukturOrganisasiResource(false, 'Data Struktur Pengurus Organisasi tidak ditemukan', null);
    }
}
