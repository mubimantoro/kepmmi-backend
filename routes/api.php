<?php

use App\Http\Controllers\Api\Admin\BidangController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\KegiatanController;
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

        Route::apiResource('/roles', RoleController::class);

        Route::apiResource('/users', UserController::class);

        Route::get('/categories/all', [CategoryController::class, 'all']);

        Route::apiResource('/categories', CategoryController::class);
        Route::apiResource('/kegiatan', KegiatanController::class);

        Route::apiResource('/bidang', BidangController::class);
        Route::apiResource('/program-kerja', ProgramKerjaController::class);
    });
});
