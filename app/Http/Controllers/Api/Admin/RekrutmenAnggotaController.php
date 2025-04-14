<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekrutmenAnggotaResource;
use App\Http\Resources\UserResource;
use App\Models\RekrutmenAnggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekrutmenAnggotaController extends Controller
{

    public function index(Request $request)
    {
        $rekrutmenAnggota = RekrutmenAnggota::with(['user', 'periodeRekrutmen'])
            ->where('periode_rekrutmen_anggota_id', $request->periode_id)
            ->paginate(10);

        return new RekrutmenAnggotaResource(true, 'List data Pendaftaran Anggota berhasil diperoleh!', $rekrutmenAnggota);
    }

    public function updateStatusRekrutmen(Request $request, $id)
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

        if ($request->status === 'Diterima') {
            $user = User::find($pendaftaran->user_id);
            if ($user) {
                $user->assignRole('Anggota');
            }
        }

        return new RekrutmenAnggotaResource(true, 'Status pendaftaran berhasil diperbarui', $pendaftaran->load(['user', 'periodeRekrutmen']));
    }
}
