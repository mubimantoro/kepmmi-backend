<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(['permission:permissions.index'], only: ['index', 'all']),
        ];
    }

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
