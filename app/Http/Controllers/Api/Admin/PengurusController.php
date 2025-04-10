<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengurusResource;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengurusController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengurus = Pengurus::create([
            'jabatan' => $request->jabatan,
            'user_id' => auth()->guard('api')->user()->id
        ]);

        if ($pengurus) {
            return new PengurusResource(true, 'Data Pengurus berhasil disimpan!', $pengurus);
        }

        return new PengurusResource(false, 'Data Pengurus gagal disimpan!', null);
    }
}
