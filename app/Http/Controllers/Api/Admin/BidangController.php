<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidangResource;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller
{

    public function index()
    {
        $bidangs = Bidang::when(request()->search, function ($bidangs) {
            $bidangs = $bidangs->where('nama', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $bidangs->appends(['search' => request()->search]);
        return new BidangResource(true, 'List Bidang Organisasi', $bidangs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tugas' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bidang = Bidang::create([
            'nama' => $request->nama,
            'tugas' => $request->tugas
        ]);

        if ($bidang) {
            return new BidangResource(true, 'Data Bidang berhasil disimpan!', $bidang);
        }

        return new BidangResource(false, 'Data Bidang gagal disimpan!', $bidang);
    }

    public function destroy(Bidang $bidang)
    {
        if ($bidang->delete()) {
            return new BidangResource(true, 'Data Bidang Organisasi berhasil dihapus!', null);
        }

        return new BidangResource(false, 'Data Bidang Organisasi gagal dihapus!', null);
    }

    public function all()
    {
        $bidangs = Bidang::latest()->get();
        return new BidangResource(true, 'List data Bidang Organisasi', $bidangs);
    }
}
