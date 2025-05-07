<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'Sekretaris Umum',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'Bidang 4',
            'guard_name' => 'api',
        ]);

        Role::create([
            'name' => 'Guest',
            'guard_name' => 'api'
        ]);
    }
}
