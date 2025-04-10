<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekrutmenAnggotaResource;
use App\Models\PeriodeRekrutmenAnggota;
use App\Models\RekrutmenAnggota;
use Illuminate\Http\Request;

class PendaftaranAnggotaController extends Controller
{
    public function store()
    {
        $periodeIsAktif = PeriodeRekrutmenAnggota::where('status', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->first();

        if (!$periodeIsAktif) {
            return new RekrutmenAnggotaResource(false, 'Tidak ada periode pendaftaran anggota yang aktif!', null);
        }

        $user = auth()->guard('api')->user();
        $sudahDaftar = RekrutmenAnggota::where('user_id', $user->id)
            ->where('periode_rekrutmen_anggota_id', $periodeIsAktif->id)
            ->exists();

        if ($sudahDaftar) {
            return new RekrutmenAnggotaResource(false, 'Anda sudah mendaftar pada periode rekrutmen ini!', null);
        }

        $pendaftaran = RekrutmenAnggota::create([
            'user_id' => $user->id,
            'periode_rekrutmen_anggota_id' => $periodeIsAktif->id,
            'status' => 'pending'
        ]);

        return new RekrutmenAnggotaResource(true, 'Pendaftaran berhasil dilakukan!', $pendaftaran);
    }
}
