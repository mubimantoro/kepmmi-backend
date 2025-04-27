<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramKerjaResource;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $programKerjas = ProgramKerja::with('bidang')->latest()->paginate(10);

        return new ProgramKerjaResource(true, 'List data Program Kerja', $programKerjas);
    }
}
