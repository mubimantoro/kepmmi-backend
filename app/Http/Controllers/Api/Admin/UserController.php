<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['permission:users.index'], only: ['index']),
            new Middleware(['permission:users.create'], only: ['store']),
            new Middleware(['permission:users.edit'], only: ['update']),
            new Middleware(['permission:users.delete'], only: ['destroy']),
        ];
    }

    public function index()
    {
        $users = User::when(request()->search, function ($users) {
            $users = $users->where('nama_lengkap', 'like', '%' . request()->search . '%');
        })->with('roles')->latest()->paginate(5);

        $users->appends(['search' => request()->search]);

        return new UserResource(true, 'List Data Users', $users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
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

    public function show($id)
    {
        $user = User::with('roles')->whereId($id)->first();

        if ($user) {
            return new UserResource(true, 'Detail data User', $user);
        }

        return new UserResource(false, 'Detail data User tidak ditemukan', null);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password == "") {
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email
            ]);
        } else {
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        $user->syncRoles($request->roles);

        if ($user) {
            return new UserResource(true, 'Data User berhasil diperbarui!', $user);
        }

        return new UserResource(false, 'Data User gagal diperbarui!', null);
    }


    public function destroy(User $user)
    {
        if ($user->delete()) {
            return new UserResource(true, 'Data user berhasil dihapus', null);
        }

        return new UserResource(false, 'Data user gagal dihapus', null);
    }
}
