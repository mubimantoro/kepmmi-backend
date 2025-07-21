<?php

use App\Http\Controllers\Api\Admin\AnggotaController;
use App\Http\Controllers\Api\Admin\BidangController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\JenisAnggotaController;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\KegiatanController;
use App\Http\Controllers\Api\Admin\PamfletController;
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
    // kegiatan
    Route::get('/kegiatan', [App\Http\Controllers\Api\Public\KegiatanController::class, 'index']);
    Route::get('/kegiatan/{slug}', [App\Http\Controllers\Api\Public\KegiatanController::class, 'show']);
    Route::get('/kegiatan-home', [App\Http\Controllers\Api\Public\KegiatanController::class, 'homePage']);
    Route::post('/kegiatan/store-image', [App\Http\Controllers\Api\Public\KegiatanController::class, 'storeImageKegiatan']);

    Route::get('/sliders', [App\Http\Controllers\Api\Public\SliderController::class, 'index']);
    // pamflet
    Route::get('/pamflet', [App\Http\Controllers\Api\Public\PamfletController::class, 'index']);
    Route::get('/pamflet/{id}', [App\Http\Controllers\Api\Public\PamfletController::class, 'show']);
    Route::get('/pamflet-home', [App\Http\Controllers\Api\Public\PamfletController::class, 'homePage']);
    Route::get('/profil-organisasi', [App\Http\Controllers\Api\Public\ProfilOrganisasiController::class, 'index']);
    Route::get('/struktur-organisasi', [App\Http\Controllers\Api\Public\StrukturOrganisasiController::class, 'index']);
    Route::get('/program-kerja', [App\Http\Controllers\Api\Public\ProgramKerjaController::class, 'index']);
    Route::get('/bidang', [App\Http\Controllers\Api\Public\ProgramKerjaController::class, 'getBidang']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/profile', [App\Http\Controllers\Api\Public\UserProfileController::class, 'show']);
    Route::post('/profile/update', [App\Http\Controllers\Api\Public\UserProfileController::class, 'update']);

    Route::post('/rekrutmen-anggota/daftar', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'store']);

    Route::get('/riwayat-pendaftaran', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'riwayatPendaftaran']);
    Route::get('/detail-pendaftaran/{id}', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'detailPendaftaran']);
    Route::put('/batalkan-pendaftaran/{id}', [App\Http\Controllers\Api\Public\PendaftaranAnggotaController::class, 'batalkanPendaftaran']);

    Route::get('/periode-rekrutmen/active', [App\Http\Controllers\Api\Public\PeriodeRekrutmenAnggotaController::class, 'index']);
    Route::get('/anggota', AnggotaContoller::class);
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/dashboard', DashboardController::class);
        // permissions
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::get('/permissions/all', [PermissionController::class, 'all']);
        // roles
        Route::get('/roles/all', [RoleController::class, 'all']);
        Route::apiResource('/roles', RoleController::class);
        // users
        Route::apiResource('/users', UserController::class);
        // profil organisasi
        Route::apiResource('/profil-organisasi', ProfilOrganisasiController::class);
        // struktur organisasi
        Route::apiResource('/struktur-organisasi', StrukturOrganisasiController::class)->middleware('permission:struktur_organisasi.index|struktur_organisasi.create|struktur_organisasi.delete');
        // bidang
        Route::apiResource('/bidang', BidangController::class);
        Route::get('/bidangs/all', [BidangController::class, 'all']);
        // Kategori
        Route::get('/categories/all', [KategoriController::class, 'all']);
        Route::apiResource('/categories', KategoriController::class);
        // kegiatan
        Route::apiResource('/kegiatan', KegiatanController::class);
        // program kerja
        Route::apiResource('/program-kerja', ProgramKerjaController::class);
        // sliders
        Route::apiResource('/sliders', SliderController::class);
        // pamflet
        Route::apiResource('/pamflet', PamfletController::class)->middleware('permission:pamflet.index|pamflet.store|pamflet.delete');
        // periode rekrutmen
        Route::get('/periode-rekrutmen/all', [PeriodeRekrutmenAnggotaController::class, 'all'])->middleware('permission:periode_rekrutmen_anggota.index');
        Route::apiResource('/periode-rekrutmen', PeriodeRekrutmenAnggotaController::class)->middleware('permission:periode_rekrutmen_anggota.index|periode_rekrutmen_anggota.store|periode_rekrutmen_anggota.delete');
        // jenis anggota
        Route::get('/jenis-anggota/all', [JenisAnggotaController::class, 'all'])->middleware('permission:jenis_anggota');
        // anggota
        Route::apiResource('/anggota', AnggotaController::class)->middleware('permission:anggota.index|anggota.store|anggota.show');
        // rekrutmen anggota
        Route::get('/rekrutmen-anggota', [RekrutmenAnggotaController::class, 'index']);
        Route::get('/rekrutmen-anggota/{id}', [RekrutmenAnggotaController::class, 'show']);
        Route::put('/rekrutmen-anggota/{id}/status', [RekrutmenAnggotaController::class, 'updateStatusRekrutmen']);
    });
});
