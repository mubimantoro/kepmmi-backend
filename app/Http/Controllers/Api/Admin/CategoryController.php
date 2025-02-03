<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::when(request()->search, function ($category) {
            $category = $category->where('nama', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $category->appends(['search' => request()->search]);

        return new CategoryResource(true, 'List Data Kategori Kegiatan', $category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'nama' => $request->nama
        ]);

        if ($category) {
            return new CategoryResource(true, 'Data kategori kegiatan berhasil disimpan', $category);
        }

        return new CategoryResource(false, 'Data kategori kegiatan gagal disimpan', null);
    }

    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return new CategoryResource(true, 'Data kategori kegiatan berhasil dihapus', null);
        }

        return new CategoryResource(false, 'Data kategori kegiatan gagal dihapus', null);
    }

    public function all()
    {
        $kategori = Category::latest()->get();
        return new CategoryResource(true, 'List data kategori kegiatan', $kategori);
    }
}
