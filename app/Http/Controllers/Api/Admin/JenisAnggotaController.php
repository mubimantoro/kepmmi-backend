<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\JenisAnggotaResource;
use App\Models\JenisAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisAnggotaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jenisAnggota = JenisAnggota::create([
            'nama' => $request->nama,
        ]);

        return new JenisAnggotaResource(true, 'Data Jenis Anggota berhasil disimpan!', $jenisAnggota);
    }

    public function destroy(string $id)
    {
        $jenisAnggota = JenisAnggota::find($id);

        if (!$jenisAnggota) {
            return new JenisAnggotaResource(false, 'Data Jenis Anggota tidak ditemukan!', null);
        }

        if ($jenisAnggota->anggotas()->count() > 0) {
            return new JenisAnggotaResource(false, 'Tidak dapat menghapus Jenis Anggota yang sedang digunakan oleh anggota!', null);
        }

        $jenisAnggota->delete();

        return new JenisAnggotaResource(true, 'Data Jenis Anggota berhasil dihapus!', null);
    }
}
