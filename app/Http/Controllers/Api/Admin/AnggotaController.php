<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnggotaResource;
use App\Models\Anggota;
use App\Models\RekrutmenAnggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:anggota.index'], only: ['index', 'show']),
            new Middleware(['permission:anggota.update_status_anggota'], only: ['store']),
            new Middleware(['permission:anggota.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $anggota = User::with([
            'profile',
            'rekrutmenAnggota' => function ($query) {
                $query->where('status', 'diterima');
            },
            'anggota',
            'anggota.jenisAnggota'
        ])
            ->whereHas('rekrutmenAnggota', function ($query) {
                $query->where('status', 'diterima');
            })
            ->latest()
            ->paginate(5);


        return new AnggotaResource(true, 'List data User', $anggota);
    }

    public function show($id)
    {
        $anggota = User::with([
            'profile',
            'rekrutmenAnggota' => function ($query) {
                $query->where('status', 'diterima');
            },
            'rekrutmenAnggota.periodeRekrutmenAnggota',
            'anggota',
            'anggota.jenisAnggota'
        ])
            ->where('id', $id)
            ->first();

        return new AnggotaResource(true, 'Detail data Anggota', $anggota);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'jenis_anggota_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hasAcceptedRekrutmen = RekrutmenAnggota::where('user_id', $request->user_id)
            ->where('status', 'diterima')
            ->exists();

        if (!$hasAcceptedRekrutmen) {
            return response()->json([
                'success' => false,
                'message' => 'User belum diterima pada proses Rekrutmen Anggota!'
            ]);
        }

        $anggota = Anggota::updateOrCreate(
            ['user_id' => $request->user_id],
            ['jenis_anggota_id' => $request->jenis_anggota_id],
        );

        $anggota->load(['user', 'jenisAnggota']);

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
