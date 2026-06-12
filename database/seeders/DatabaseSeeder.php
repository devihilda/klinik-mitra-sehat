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

        // Create Officer Account for Scan / Testing
        User::create([
            'name' => 'Petugas Klinik',
            'email' => 'petugas@klinik.test',
            'password' => 'admin12345', // Auto-hashed via User model cast
            'role' => 'petugas',
        ]);

        // Create Patient Account for Scan / Testing
        $patientUser = User::create([
            'name' => 'Pasien Klinik',
            'email' => 'pasien@klinik.test',
            'password' => 'pasien12345', // Auto-hashed via User model cast
            'role' => 'pasien',
        ]);

        // Create associated Patient profile
        $patientUser->patient()->create([
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Sehat Sentosa No. 10',
        ]);
    }
}
