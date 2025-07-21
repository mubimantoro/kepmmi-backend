<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekrutmenAnggotaResource;
use App\Models\RekrutmenAnggota;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class RekrutmenAnggotaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:rekrutmen_anggota.index'], only: ['index', 'show']),
            new Middleware(['permission:rekrutmen_anggota.update_status_rekrutmen'], only: ['updateStatusRekrutmen']),
        ];
    }

    public function index()
    {
        $rekrutmenAnggota = RekrutmenAnggota::with(['user' => function ($query) {
            $query->with('profile');
        }, 'periodeRekrutmenAnggota'])
            ->when(request()->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . request()->search . '%');
                });
            })
            ->when(request()->periode_rekrutmen_anggota_id, function ($query) {
                $query->where('periode_rekrutmen_anggota_id', request()->periode_rekrutmen_anggota_id);
            })
            ->latest()
            ->paginate(15);

        $rekrutmenAnggota->appends([
            'search' => request()->search,
            'periode_rekrutmen_anggota_id' => request()->periode_rekrutmen_anggota_id
        ]);


        return new RekrutmenAnggotaResource(true, 'List data Pendaftaran Anggota', $rekrutmenAnggota);
    }

    public function show($id)
    {
        $pendaftaran = RekrutmenAnggota::with(['user' => function ($query) {
            $query->with('profile');
        },  'periodeRekrutmenAnggota'])->findOrFail($id);
        return new RekrutmenAnggotaResource(true, 'Detail Pendaftaran Anggota!', $pendaftaran);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->fails(), 422);
        }

        $pendaftaran = RekrutmenAnggota::findOrFail($id);
        $pendaftaran->update([
            'status' => $request->status
        ]);

        return new RekrutmenAnggotaResource(true, 'Status pendaftaran berhasil diperbarui', $pendaftaran->load(['user', 'periodeRekrutmenAnggota']));
    }
}
