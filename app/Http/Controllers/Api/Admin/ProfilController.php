<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilResource;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function index() {}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'asal_kampus' => 'required',
            'jurusan' => 'required',
            'angkatan_akademik' => 'required',
            'asal_daerah' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('avatar')) {
            Storage::disk('local')->delete('public/avatar' . basename($request->avatar));

            $avatar = $request->file('avatar');
            $avatar->storeAs('avatar', $avatar->hashName(), 'public');
        }

        $profil = Profil::updateOrCreate(
            ['user_id' => auth()->guard('api')->user()->id],
            [
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_kampus' => $request->asal_kampus,
                'jurusan' => $request->jurusan,
                'angkatan_akademik' => $request->angkatan_akademik,
                'asal_daerah' => $request->asal_daerah,
                'avatar' => $avatar->hashName(),
            ]
        );

        if ($profil) {
            return new ProfilResource(true, 'Data Profil berhasil disimpan!', $profil);
        }

        return new ProfilResource(false, 'Data Profil gagal disimpan!', null);
    }

    public function show($id) {}

    public function update(Request $request, Profil $profil) {}

    public function destroy(Profil $profil) {}
}
