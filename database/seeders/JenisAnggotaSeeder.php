<?php

namespace Database\Seeders;

use App\Models\JenisAnggota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisAnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisAnggota::create([
            'nama' => 'Anggota Muda'
        ]);

        JenisAnggota::create([
            'nama' => "Anggota Biasa"
        ]);

        JenisAnggota::create([
            'nama' => 'Anggota Luar Biasa'
        ]);
    }
}
