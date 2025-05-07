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
            'is_aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->is_aktif) {
            PeriodeRekrutmenAnggota::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        $periode = PeriodeRekrutmenAnggota::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_aktif' => $request->is_aktif ?? false,
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

    public function all()
    {
        $periode = PeriodeRekrutmenAnggota::latest()->get();
        return new PeriodeRekrutmenAnggotaResource(true, 'List Periode Rekrutmen Anggota', $periode);
    }
}
