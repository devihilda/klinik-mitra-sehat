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
        // User::factory(10)->create();

        // Versi Aman (Password di-hash & Kuat):
        // \App\Models\User::create([
        //     'name' => 'Petugas Klinik',
        //     'email' => 'petugas@klinik.test',
        //     'password' => \Illuminate\Support\Facades\Hash::make('PetugasKlinik123'),
        //     'role' => 'petugas',
        // ]);

        // Versi Rentan (Plaintext & Lemah):
        User::create([
            'name' => 'Petugas Klinik',
            'email' => 'petugas@klinik.test',
            'password' => 'admin123',
            'role' => 'petugas',
        ]);
    }
}
