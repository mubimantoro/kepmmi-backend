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
        $pengurus = Pengurus::with('user')->latest()->paginate(5);
        Log::info('Data pengurus: ' . $pengurus->count());
        return new PengurusResource(true, 'List data Pengurus', $pengurus);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $avatar = $request->file('avatar');
        $avatar->storeAs('pengurus', $avatar->hashName());

        $pengurus = Pengurus::create([
            'jabatan' => $request->jabatan,
            'avatar' => $avatar->hashName(),
            'user_id' => auth()->guard('api')->user()->id
        ]);

        if ($pengurus) {
            return new PengurusResource(true, 'Data Pengurus berhasil disimpan!', $pengurus);
        }

        return new PengurusResource(false, 'Data Pengurus gagal disimpan!', null);
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
