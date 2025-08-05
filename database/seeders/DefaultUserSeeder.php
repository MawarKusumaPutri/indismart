<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user staff
        User::create([
            'name' => 'Staff Indismart',
            'email' => 'staff@indismart.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // Buat user mitra
        User::create([
            'name' => 'Mitra Indismart',
            'email' => 'mitra@indismart.com',
            'password' => Hash::make('password123'),
            'role' => 'mitra',
        ]);
    }
}