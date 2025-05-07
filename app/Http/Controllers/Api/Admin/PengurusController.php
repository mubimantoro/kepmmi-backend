<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengurusResource;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengurusController extends Controller
{
    public function index()
    {
        $pengurus = Pengurus::when(request()->search, function ($pengurus) {
            $pengurus = $pengurus->where('nama_lengkap', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $pengurus->appends(['search' => request()->search]);

        return new PengurusResource(true, 'List data Pengurus', $pengurus);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'avatar' => 'required|mimes:png,jpg,jpeg|max:10240',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $avatar = $request->file('avatar');
        $avatar->storeAs('pengurus', $avatar->hashName(), 'public');

        $pengurus = Pengurus::create([
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'avatar' => $avatar->hashName(),
        ]);

        if ($pengurus) {
            return new PengurusResource(true, 'Data Pengurus berhasil disimpan!', $pengurus);
        }

        return new PengurusResource(false, 'Data Pengurus gagal disimpan!', null);
    }

    public function show($id)
    {
        $pengurus = Pengurus::whereId($id)->first();

        if ($pengurus) {
            return new PengurusResource(true, 'Detail data Pengurus!', $pengurus);
        }

        return new PengurusResource(false, 'Detail data Pengurus tidak ditemukan!', null);
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => "required",
            'jabatan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('avatar')) {
            Storage::disk('local')->delete('public/pengurus/' . basename($pengurus->avatar));

            $avatar = $request->file('avatar');
            $avatar->storeAs('pengurus', $avatar->hashName(), 'public');

            $pengurus->update([
                'avatar' => $avatar->hashName(),
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan
            ]);
        }

        $pengurus->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
        ]);

        if ($pengurus) {
            return new PengurusResource(true, 'Data Pengurus berhasil diupdate!', $pengurus);
        }

        return new PengurusResource(false, 'Data Pengurus gagal diupdate!', null);
    }

    public function destroy(Pengurus $pengurus)
    {
        Storage::disk('local')->delete('public/pengurus' . basename($pengurus->avatar));

        if ($pengurus->delete()) {
            return new PengurusResource(true, 'Data Pengurus berhasil dihapus!', null);
        }

        return new PengurusResource(false, 'Data Pengurus gagal dihapus!', null);
    }
}
