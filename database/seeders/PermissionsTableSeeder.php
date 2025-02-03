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
        Permission::create(['name' => 'kategori', 'guard_name' => 'api']);
        Permission::create(['name' => 'bidang', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan', 'guard_name' => 'api']);
    }
}
