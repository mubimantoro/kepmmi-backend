<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnggotaResource;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::with(['user', 'jenisAnggota'])->get();

        return new AnggotaResource(true, 'List data Jenis Anggota', $anggotas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_anggota_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $anggota = Anggota::create([
            'jenis_anggota_id' => $request->jenis_anggota_id
        ]);

        return new AnggotaResource(true, 'Data Anggota berhasil disimpan!', $anggota);
    }

    public function destroy(string $id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return new AnggotaResource(false, 'Data Anggota tidak ditemukan', null);
        }

        $anggota->delete();

        return new AnggotaResource(true, 'Data Anggota berhasil dihapus!', null);
    }
}
