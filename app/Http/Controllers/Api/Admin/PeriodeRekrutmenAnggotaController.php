<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodeRekrutmenAnggotaResource;
use App\Models\PeriodeRekrutmenAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodeRekrutmenAnggotaController extends Controller
{

    public function index()
    {
        $periodes = PeriodeRekrutmenAnggota::latest()->paginate(5);

        return new PeriodeRekrutmenAnggotaResource(true, 'List data Periode Rekrutmen Anggota', $periodes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->status) {
            PeriodeRekrutmenAnggota::where('status', true)->update(['status' => false]);
        }

        $periode = PeriodeRekrutmenAnggota::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status ?? false,
        ]);

        if ($periode) {
            return new PeriodeRekrutmenAnggotaResource(true, 'Data Periode Rekrutmen Anggota berhasil disimpan!', $periode);
        }

        return new PeriodeRekrutmenAnggotaResource(false, 'Data Periode Rekrutmen Anggota gagal disimpan!', null);
    }

    public function destroy(PeriodeRekrutmenAnggota $periode)
    {
        if ($periode->delete()) {
            return new PeriodeRekrutmenAnggotaResource(true, 'Data Periode Rekrutmen Anggota berhasil dihapus!', null);
        }


        return new PeriodeRekrutmenAnggotaResource(false, 'Data Periode Rekrutmen Anggota gagal dihapus!', null);
    }
}
