<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create staff user
        User::create([
            'name' => 'Staff Test',
            'email' => 'staff@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create mitra user
        User::create([
            'name' => 'Mitra Test',
            'email' => 'mitra@test.com',
            'password' => Hash::make('password'),
            'role' => 'mitra',
        ]);

        // Create additional test users
        User::create([
            'name' => 'Admin Staff',
            'email' => 'admin@smartped.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'PT Telkom Indonesia',
            'email' => 'contact@telkom.co.id',
            'password' => Hash::make('password'),
            'role' => 'mitra',
        ]);
    }
}
