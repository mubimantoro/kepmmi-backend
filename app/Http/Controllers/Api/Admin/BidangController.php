<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BidangResource;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:bidang.index'], only: ['index', 'all']),
            new Middleware(['permission:bidang.create'], only: ['store']),
            new Middleware(['permission:bidang.edit'], only: ['update']),
            new Middleware(['permission:bidang.delete'], only: ['destroy']),
        ];
    }


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

    public function show($id)
    {
        $bidang = Bidang::whereId($id)->first();

        if ($bidang) {
            return new BidangResource(true, 'Detail data Bidang!', $bidang);
        }

        return new BidangResource(false, 'Detail data Bidang tidak ditemukan!', null);
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tugas' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bidang->update([
            'nama' => $request->nama,
            'tugas' => $request->tugas
        ]);

        if ($bidang) {
            return new BidangResource(true, 'Data Bidang berhasil diupdate!', $bidang);
        }

        return new BidangResource(false, 'Data Bidang gagal diupdate!', null);
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
