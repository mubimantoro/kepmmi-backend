<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::when(request()->search, function ($permissions) {
            $permissions = $permissions->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $permissions->appends(['search' => request()->search]);

        return new PermissionResource(true, 'List data permissions', $permissions);
    }

    public function all()
    {
        $permissions = Permission::latest()->get();
        return new PermissionResource(true, 'Daftar data permission', $permissions);
    }
}
