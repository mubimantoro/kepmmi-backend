<?php

use App\Http\Controllers\Api\Admin\AnggotaController;
use App\Http\Controllers\Api\Admin\BidangController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\JenisAnggotaController;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\KegiatanController;
use App\Http\Controllers\Api\Admin\PamfletController;
use App\Http\Controllers\Api\Admin\PengurusController;
use App\Http\Controllers\Api\Admin\PeriodeRekrutmenAnggotaController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\ProfilOrganisasiController;
use App\Http\Controllers\Api\Admin\ProgramKerjaController;
use App\Http\Controllers\Api\Admin\RekrutmenAnggotaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\StrukturOrganisasiController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Public\AnggotaContoller;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'index']);
Route::post('/login', [LoginController::class, 'index']);
Route::post('/register', [RegisterController::class, 'index']);

Route::prefix('public')->group(function () {
    Route::get('/categories', [App\Http\Controllers\Api\Public\CategoryController::class, 'index']);
    Route::get('/pengurus', [App\Http\Controllers\Api\Admin\PengurusController::class, 'index']);
    Route::get('/kegiatan', [App\Http\Controllers\Api\Public\KegiatanController::class, 'index']);
    Route::get('/kegiatan/{slug}', [App\Http\Controllers\Api\Public\KegiatanController::class, 'show']);
    Route::get('/kegiatan-home', [App\Http\Controllers\Api\Public\KegiatanController::class, 'homePage']);
    Route::post('/kegiatan/store-image', [App\Http\Controllers\Api\Public\KegiatanController::class, 'storeImageKegiatan']);
    Route::get('/sliders', [App\Http\Controllers\Api\Public\SliderController::class, 'index']);
    Route::get('/pamflet', [App\Http\Controllers\Api\Public\PamfletController::class, 'index']);
    Route::get('/program-kerja', [App\Http\Controllers\Api\Public\ProgramKerjaController::class, 'index']);
    Route::get('/profil-organisasi', [App\Http\Controllers\Api\Public\ProfilOrganisasiController::class, 'index']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/profile', [App\Http\Controllers\Api\Public\UserProfileController::class, 'show']);
    Route::post('/profile/update', [App\Http\Controllers\Api\Public\UserProfileController::class, 'update']);

    Route::post('/rekrutmen-anggota/daftar', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'store']);
    Route::get('/periode-rekrutmen/active', [App\Http\Controllers\Api\Public\PeriodeRekrutmenAnggotaController::class, 'index']);
    Route::get('/rekrutmen-anggota/riwayat', [App\Http\Controllers\Api\Public\RiwayatPendaftaranAnggotaController::class]);
    Route::get('/anggota', AnggotaContoller::class);
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/dashboard', DashboardController::class);
        // permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.index');
        Route::get('/permissions/all', [PermissionController::class, 'all'])->middleware('permission:permissions.index');
        // roles
        Route::get('/roles/all', [RoleController::class, 'all'])->middleware('permission:roles.index');
        Route::apiResource('/roles', RoleController::class)->middleware('permission:roles.index');
        // users
        Route::apiResource('/users', UserController::class)->middleware('permission:users.index');
        // profil organisasi
        Route::apiResource('/profil-organisasi', ProfilOrganisasiController::class)->middleware('permission:profil_organisasi.index|profil_organisasi.create|profil_organisasi.show|profil_organisasi.delete');
        // struktur organisasi
        Route::apiResource('/struktur-organisasi', StrukturOrganisasiController::class)->middleware('permission:struktur_organisasi.index|struktur_organisasi.create|struktur_organisasi.delete');
        // bidang
        Route::get('/bidangs/all', [BidangController::class, 'all'])->middleware('permission:bidang.index');
        Route::apiResource('/bidang', BidangController::class)->middleware('permission:bidang.index');
        // Category
        Route::get('/categories/all', [KategoriController::class, 'all'])->middleware('permission:kategori.index');
        Route::apiResource('/categories', KategoriController::class)->middleware('permission:kategori.index|kategori.create');
        // kegiatan
        Route::apiResource('/kegiatan', KegiatanController::class)->middleware('permission:kegiatan.index|kegiatan.store|kegiatan.update|kegiatan.delete');
        // program kerja
        Route::apiResource('/program-kerja', ProgramKerjaController::class)->middleware('permission:program_kerja.index|progam_kerja.store|program_kerja.update|program_kerja.delete');
        // sliders
        Route::apiResource('/sliders', SliderController::class)->middleware('permission:sliders.index|sliders.store|sliders.delete');
        // pamflet
        Route::apiResource('/pamflet', PamfletController::class)->middleware('permission:pamflet.index|pamflet.store|pamflet.delete');
        // periode rekrutmen
        Route::get('/periode-rekrutmen/all', [PeriodeRekrutmenAnggotaController::class, 'all'])->middleware('permission:periode_rekrutmen_anggota.index');
        Route::apiResource('/periode-rekrutmen', PeriodeRekrutmenAnggotaController::class)->middleware('permission:periode_rekrutmen_anggota.index|periode_rekrutmen_anggota.store|periode_rekrutmen_anggota.delete');
        // pengurus
        Route::apiResource('/pengurus', PengurusController::class)->middleware('permission:pengurus');
        // jenis anggota
        Route::get('/jenis-anggota/all', [JenisAnggotaController::class, 'all'])->middleware('permission:jenis_anggota');
        // anggota
        Route::apiResource('/anggota', AnggotaController::class)->middleware('permission:anggota.index|anggota.store|anggota.show');
        // rekrutmen anggota
        Route::get('/rekrutmen-anggota', [RekrutmenAnggotaController::class, 'index'])->middleware('permission:rekrutmen_anggota.index');
        Route::get('/rekrutmen-anggota/{id}', [RekrutmenAnggotaController::class, 'show'])->middleware('permission:rekrutmen_anggota.show');
        Route::put('/rekrutmen-anggota/{id}/status', [RekrutmenAnggotaController::class, 'updateStatusRekrutmen'])->middleware('permission:rekrutmen_anggota.update');
    });
});
