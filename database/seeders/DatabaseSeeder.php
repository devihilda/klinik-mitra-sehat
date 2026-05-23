<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Budi Pasien',
            'username' => 'budi',
            'password' => 'budi123',
            'role' => 'pasien',
        ]);

        User::create([
            'name' => 'Siti Petugas',
            'username' => 'siti',
            'password' => 'siti123',
            'role' => 'petugas',
        ]);
    }
}
