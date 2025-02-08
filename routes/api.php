<?php

use App\Http\Controllers\Api\Admin\BidangController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\KegiatanController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\ProgramKerjaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/dashboard', DashboardController::class);

        // permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions');
        Route::get('/permissions/all', [PermissionController::class, 'all'])->middleware('permission:permissions');

        // roles
        Route::get('/roles/all', [RoleController::class, 'all'])->middleware('permission:roles');
        Route::apiResource('/roles', RoleController::class)->middleware('permission:roles');

        // users
        Route::apiResource('/users', UserController::class)->middleware('permission:users');

        // Category
        Route::apiResource('/categories', KategoriController::class)->middleware('permission:kategori');
        Route::get('/categories/all', [KategoriController::class, 'all'])->middleware('permission:kategori');


        Route::apiResource('/kegiatan', KegiatanController::class);

        Route::apiResource('/bidang', BidangController::class);
        Route::apiResource('/program-kerja', ProgramKerjaController::class);
    });
});
