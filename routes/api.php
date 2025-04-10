<?php

use App\Http\Controllers\Api\Admin\BidangController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\GaleriController;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\KegiatanController;
use App\Http\Controllers\Api\Admin\PamfletController;
use App\Http\Controllers\Api\Admin\PeriodeRekrutmenAnggotaController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\ProfilController;
use App\Http\Controllers\Api\Admin\ProgramKerjaController;
use App\Http\Controllers\Api\Admin\RekrutmenAnggotaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\StrukturPengurusController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'index']);
Route::post('/register', [RegisterController::class, 'index']);

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
        Route::get('/categories/all', [KategoriController::class, 'all'])->middleware('permission:kategori');
        Route::apiResource('/categories', KategoriController::class)->middleware('permission:kategori');

        // kegiatan
        Route::apiResource('/kegiatan', KegiatanController::class)->middleware('permission:kegiatan');

        Route::apiResource('/bidang', BidangController::class)->middleware('permission:bidang');

        Route::apiResource('/program-kerja', ProgramKerjaController::class);

        Route::apiResource('/profil', ProfilController::class)->middleware('permission:profil');

        Route::apiResource('/struktur-pengurus', StrukturPengurusController::class)->middleware('permission:struktur_pengurus');

        Route::apiResource('/sliders', SliderController::class)->middleware('permission:sliders');

        Route::apiResource('/pamflet', PamfletController::class)->middleware('permission:pamflet');

        Route::apiResource('/periode-rekrutmen', PeriodeRekrutmenAnggotaController::class)->middleware('permission:periode_rekrutmen_anggota');

        Route::get('/rekrutmen-anggota', [RekrutmenAnggotaController::class, 'index']);
        Route::put('/rekrutmen-anggota/{id}/status', [RekrutmenAnggotaController::class, 'updateStatusRekrutmen']);
    });
});

Route::prefix('public')->group(function () {
    Route::get('/kegiatan', [App\Http\Controllers\Api\Public\KegiatanController::class, 'index']);
    Route::get('/kegiatan/{slug}', [App\Http\Controllers\Api\Public\KegiatanController::class, 'show']);
    Route::get('/kegiatan-home', [App\Http\Controllers\Api\Public\KegiatanController::class, 'homePage']);
    Route::post('/kegiatan/store-image', [App\Http\Controllers\Api\Public\KegiatanController::class, 'storeImageKegiatan']);
    Route::get('/pamflet', [App\Http\Controllers\Api\Public\PamfletController::class, 'index']);
    Route::get('/sliders', [App\Http\Controllers\Api\Public\SliderController::class, 'index']);

    Route::post('/rekrutmen-anggota/daftar', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'store']);
});
