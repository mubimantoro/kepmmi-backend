<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = auth()->guard('api')->user();
        $user->load('profile');
        return new UserProfileResource(true, 'Data Profil User', $user);
    }

    public function update(Request $request)
    {

        $user = auth()->guard('api')->user();

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes|nullable|string',
            'alamat' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'asal_kampus' => 'required|string',
            'jurusan' => 'required|string',
            'angkatan_akademik' => 'required|string',
            'asal_daerah' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('avatar')) {
            if ($user->avatar) {
                Storage::disk('local')->delete('public/avatars' . basename($user->avatar));
            }

            $avatar = $request->file('avatar');
            $avatar->storeAs('avatars', $avatar->hashName(), 'public');

            $user->update([
                'nama_lengkap' => $request->nama_lengkap ?? $user->nama_lengkap,
                'avatar' => $avatar->hashName()
            ]);
        } else {
            $user->update([
                'nama_lengkap' => $request->nama_lengkap ?? $user->nama_lengkap,
            ]);
        }

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_kampus' => $request->asal_kampus,
                'jurusan' => $request->jurusan,
                'angkatan_akademik' => $request->angkatan_akademik,
                'asal_daerah' => $request->asal_daerah
            ]
        );

        $user->load('profile');

        if ($user && $profile) {
            return new UserProfileResource(true, 'Profil berhasil diperbarui!', $user);
        }

        return new UserProfileResource(false, 'Profil gagal diperbarui!', null);
    }
}
