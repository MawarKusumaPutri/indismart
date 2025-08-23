<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        // Update atau buat user karyawan (hanya 1 email)
        User::updateOrCreate(
            ['email' => 'karyawan@telkom.co.id'],
            [
                'name' => 'Karyawan Indismart',
                'password' => Hash::make('Ped123*'),
                'role' => 'staff',
            ]
        );

        // Update atau buat user mitra
        User::updateOrCreate(
            ['email' => 'mitra@indismart.com'],
            [
                'name' => 'Mitra Indismart',
                'password' => Hash::make('password123'),
                'role' => 'mitra',
            ]
        );
    }
}