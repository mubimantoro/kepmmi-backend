<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'adminkepmmi@gmail.com',
            'password' => bcrypt('kepmmigtlo'),
        ]);

        $role = Role::find(1);

        $user = User::find(1);
        $user->assignRole($role->nama_lengkap);
    }
}
