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

        $user = auth()->guard('api')->user();

        if (!$this->isProfileComplete($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Silakan lengkapi profil Anda terlebih dahulu'
            ]);
        }

        $periodeIsAktif = PeriodeRekrutmenAnggota::where('is_aktif', true)
            ->first();

        if (!$periodeIsAktif) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada periode pendaftaran anggota yang aktif!'
            ]);
        }

        $sudahDaftar = RekrutmenAnggota::where('user_id', $user->id)
            ->where('periode_rekrutmen_anggota_id', $periodeIsAktif->id)
            ->exists();

        if ($sudahDaftar) {
            return response()->json([
                'status' => false,
                'message' => 'Anda sudah mendaftar pada periode rekrutmen ini!'
            ]);
        }

        $pendaftaran = RekrutmenAnggota::create([
            'user_id' => $user->id,
            'periode_rekrutmen_anggota_id' => $periodeIsAktif->id,
            'status' => 'pending'
        ]);

        return new RekrutmenAnggotaResource(true, 'Pendaftaran berhasil dilakukan!', $pendaftaran);
    }

    private function isProfileComplete($user)
    {
        if (!$user->profile) {
            return false;
        }

        $requiredFields = [
            'alamat',
            'tempat_lahir',
            'tanggal_lahir',
            'asal_kampus',
            'jurusan',
            'angkatan_akademik',
            'asal_daerah'
        ];

        foreach ($requiredFields as $field) {
            if (empty($user->profile->$field)) {
                return false;
            }
        }

        return true;
    }
}
