<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramKerjaResource;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class ProgramKerjaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:program_kerja.index'], only: ['index']),
            new Middleware(['permission:program_kerja.create'], only: ['store']),
            new Middleware(['permission:program_kerja.edit'], only: ['update']),
            new Middleware(['permission:program_kerja.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $programKerjas = ProgramKerja::with('bidang')
            ->when(request()->search, function ($query) {
                $query->where('nama', 'like', '%', request()->search . '%');
            })->latest()->paginate(5);

        $programKerjas->appends(['search' => request()->search]);

        return new ProgramKerjaResource(true, 'List data Program Kerja', $programKerjas);
    }

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
            return new ProgramKerjaResource(true, 'Data Program Kerja berhasil disimpan!', $programKerja);
        }

        return new ProgramKerjaResource(false, 'Data Program Kerja gagal disimpa!n', null);
    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        $validator  = Validator::make($request->all(), [
            'bidang_id' => 'required',
            'nama' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $programKerja->update([
            'nama' => $request->nama,
            'status' => $request->status,
            'bidang_id' => $request->bidang_id
        ]);

        if ($programKerja) {
            return new ProgramKerjaResource(true, 'Data Program Kerja berhasil diupdate!', $programKerja);
        }

        return new ProgramKerjaResource(false, 'Data Program Kerja gagal diupdate!', null);
    }

    public function destroy(ProgramKerja $programKerja)
    {
        if ($programKerja->delete()) {
            return new ProgramKerjaResource(true, 'Data Program Kerja berhasil dihapus!', null);
        }

        return new ProgramKerjaResource(false, 'Data Program Kerja gagal dihapus!', null);
    }
}
