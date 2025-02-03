<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidangResource;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama',
            'tugas'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bidang = Bidang::create([
            'nama',
            'tugas'
        ]);

        if ($bidang) {
            return new BidangResource(true, 'Data bidang berhasil ditambahkan', $bidang);
        }
    }

    public function destory(Bidang $bidang)
    {
        if ($bidang->delete()) {
            return new BidangResource(true, 'Data bidang berhasil dihapus', null);
        }

        return new BidangResource(false, 'Data bidang gagal dihapus', null);
    }
}
