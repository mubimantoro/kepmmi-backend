<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'adminkepmmi@gmail.com',
            'password' => bcrypt('kepmmigtlo'),
        ]);

        $adminDev = User::create([
            'nama_lengkap' => "Administrator Dev",
            'email' => 'adminkepmmi@dev.com',
            'password'  => bcrypt('password'),
        ]);

        $role = Role::find(1);
        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        $admin->assignRole($role->name);
        $adminDev->assignRole($role->name);
    }
}
