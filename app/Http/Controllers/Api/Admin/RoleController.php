<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::when(request()->search, function ($roles) {
            $roles = $roles->where('name', 'like', '%' . request()->search . '%');
        })->with('permissions')->latest()->paginate(5);

        $roles->appends(['search' => request()->search]);

        return new RoleResource(true, 'List Data Roles', $roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create(['name' => $request->name]);

        $role->givePermissionTo($request->permissions);

        if ($role) {
            return new RoleResource(true, 'Data Role berhasil disimpan', $role);
        }

        return new RoleResource(false, 'Data role gagal disimpan', null);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        if ($role) {
            return new RoleResource(true, 'Detail data role', $role);
        }

        return new RoleResource(false, 'Detail data role tidak ditemukan', null);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update(['name' => $role->name]);
        $role->syncPermissions($request->permissions);

        if ($role) {
            return new RoleResource(true, 'Data role berhasil diperbarui', $role);
        }

        return new RoleResource(false, 'Data role gagal diperbarui', null);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->delete()) {
            return new RoleResource(true, 'Data role berhasil dihapus', null);
        }

        return new RoleResource(false, 'Data role gagal dihapus', null);
    }

    public function all()
    {
        $roles = Role::latest()->get();
        return new RoleResource(true, 'List data roles', $roles);
    }
}
