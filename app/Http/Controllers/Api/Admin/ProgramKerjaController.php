<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramKerjaResource;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramKerjaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required',
            'nama' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $programKerja = ProgramKerja::create([
            'nama' => $request->nama,
            'status' => $request->status,
            'bidang_id' => $request->bidang_id
        ]);

        if ($programKerja) {
            return new ProgramKerjaResource(true, 'Data program kerja berhasil ditambahkan', $programKerja);
        }

        return new ProgramKerjaResource(false, 'Data program kerja gagal disimpan', null);
    }

    public function destroy(ProgramKerja $programKerja)
    {
        if ($programKerja->delete()) {
            return new ProgramKerjaResource(true, 'Data program kerja berhasil dihapus', null);
        }

        return new ProgramKerjaResource(false, 'Data program kerja gagal dihapus', null);
    }
}
