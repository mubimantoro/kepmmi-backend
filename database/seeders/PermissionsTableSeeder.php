<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // kategori
        Permission::create(['name' => 'kategori.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'kategori.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'kategori.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'kategori.delete', 'guard_name' => 'api']);
        // bidang
        Permission::create(['name' => 'bidang.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'bidang.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'bidang.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'bidang.delete', 'guard_name' => 'api']);
        // program kerja
        Permission::create(['name' => 'program_kerja.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'program_kerja.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'program_kerja.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'program_kerja.delete', 'guard_name' => 'api']);
        // kegiatan
        Permission::create(['name' => 'kegiatan.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.delete', 'guard_name' => 'api']);
        // anggota
        Permission::create(['name' => 'anggota.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'anggota.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'anggota.edit', 'guard_name' => 'api']);
        // jenis anggota
        Permission::create(['name' => 'jenis_anggota', 'guard_name' => 'api']);
        // struktur pengurus
        Permission::create(['name' => 'struktur_organisasi.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'struktur_organisasi.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'struktur_organisasi.delete', 'guard_name' => 'api']);
        // rekrutmen anggota
        Permission::create(['name' => 'rekrutmen_anggota.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'rekrutmen_anggota.update_status_rekrutmen', 'guard_name' => 'api']);
        // pamflet
        Permission::create(['name' => 'pamflet.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pamflet.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pamflet.delete', 'guard_name' => 'api']);
        // periode rekrutmen anggota
        Permission::create(['name' => 'periode_rekrutmen_anggota.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'periode_rekrutmen_anggota.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'periode_rekrutmen_anggota.delete', 'guard_name' => 'api']);
        // profil organisasi
        Permission::create(['name' => 'profil_organisasi.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'profil_organisasi.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'profil_organisasi.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'profil_organisasi.delete', 'guard_name' => 'api']);
        // permissions
        Permission::create(['name' => 'permissions.index', 'guard_name' => 'api']);
        // roles
        Permission::create(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'api']);
        // sliders
        Permission::create(['name' => 'sliders.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'sliders.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'sliders.delete', 'guard_name' => 'api']);
        // users
        Permission::create(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'api']);
    }
}
