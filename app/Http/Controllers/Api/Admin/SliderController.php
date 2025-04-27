<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->paginate(5);

        return new SliderResource(true, 'List data Sliders', $sliders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('gambar');
        $image->storeAs('sliders', $image->hashName(), 'public');

        $slider = Slider::create([
            'gambar' => $image->hashName()
        ]);

        if ($slider) {
            return new SliderResource(true, 'Data Slider berhasil disimpan!', $slider);
        }

        return new SliderResource(false, 'Data Slider gagal disimpan!', null);
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('local')->delete('public/sliders' . basename($slider->gambar));

        if ($slider->delete()) {
            return new SliderResource(true, 'Data Slider berhasil dihapus!', null);
        }

        return new SliderResource(false, 'Data Slider gagal dihapus!', null);
    }
}
