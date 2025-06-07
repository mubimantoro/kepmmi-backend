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
            'nama_lengkap' => "Administrator Testing Account",
            'email' => 'adminkepmmi@dev.com',
            'password'  => bcrypt('password'),
        ]);

        $sekumDev = User::create([
            'nama_lengkap' => 'Sekretaris Umum Testing Account',
            'email' => 'sekumkepmmi@dev.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::find(1);
        $roleSekum = Role::find(2);
        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        $admin->assignRole($role->name);
        $adminDev->assignRole($role->name);
        $sekumDev->assignRole($roleSekum->name);
    }
}
