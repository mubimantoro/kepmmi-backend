<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidangResource;
use App\Http\Resources\ProgramKerjaResource;
use App\Models\Bidang;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $programKerja = ProgramKerja::with('bidang')
            ->latest()
            ->get();

        if ($programKerja->count() > 0) {
            return new ProgramKerjaResource(true, 'List data Program Kerja', $programKerja);
        }

        return new ProgramKerjaResource(false, 'Data Program Kerja tidak ditemukan!', $programKerja);
    }

    public function getBidang()
    {
        $bidang = Bidang::with(['programKerjas'])->get();

        if ($bidang->count() > 0) {
            return new BidangResource(true, 'List data Bidang Organisasi', $bidang);
        }

        return new BidangResource(false, 'Data Bidang tidak ditemukan', $bidang);
    }
}
