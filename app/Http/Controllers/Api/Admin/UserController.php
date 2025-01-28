<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::when(request()->search, function ($users) {
            $users = $users->where('nama_lengkap', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $users->appends(['search' => request()->search]);

        return new UserResource(true, 'List Data Users', $users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'password' => 'required|'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $user->assignRole($request->roles);

        if ($user) {
            return new UserResource(true, 'Data user berhasil disimpan', $user);
        }

        return new UserResource(false, 'Data user gagal disimpan', null);
    }

    public function destroy(User $user)
    {
        if ($user->delete()) {
            return new UserResource(true, 'Data user berhasil dihapus', null);
        }

        return new UserResource(false, 'Data user gagal dihapus', null);
    }
}
