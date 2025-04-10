<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PamfletResource;
use App\Models\Pamflet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PamfletController extends Controller
{
    public function index()
    {
        $pamflets = Pamflet::when(request()->search, function ($pamflets) {
            $pamflets = $pamflets->where('caption', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $pamflets->appends(['search' => request()->search]);

        return new PamfletResource(true, 'List data Pamflet', $pamflets);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'caption' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('pamflet', $image->hashName(), 'public');

        $pamflet = Pamflet::create([
            'gambar' => $image->hashName(),
            'caption' => $request->caption,
        ]);

        if ($pamflet) {
            return new PamfletResource(true, 'Data Pamflet berhasil disimpan!', $pamflet);
        }

        return new PamfletResource(false, 'Data Pamflet gagal disimpan!', null);
    }

    public function destroy(Pamflet $pamflet)
    {
        Storage::disk('local')->delete('public/pamflet' . basename($pamflet->gambar));

        if ($pamflet->delete()) {
            return new PamfletResource(true, 'Data Pamflet berhasil dihapus!', null);
        }

        return new PamfletResource(false, 'Data Pamflet gagal dihapus!', null);
    }
}
