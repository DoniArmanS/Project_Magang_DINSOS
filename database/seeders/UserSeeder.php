<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Dinsos',
            'email' => 'admin@dinsos.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'jenis_user' => 'Admin',
        ]);

        // Peksos
        User::create([
            'name' => 'Peksos 1',
            'email' => 'peksos1@dinsos.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'jenis_user' => 'Peksos',
        ]);

        // TRC
        User::create([
            'name' => 'TRC 1',
            'email' => 'trc1@dinsos.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'jenis_user' => 'TRC',
        ]);

        // Staff Dinsos
        User::create([
            'name' => 'Staff Dinsos 1',
            'email' => 'staff1@dinsos.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'jenis_user' => 'Staff Dinsos',
        ]);

        $this->command->info('4 Users seeded: admin, peksos, trc, staff dinsos.');
    }
}