<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * Create 2 Admin Accounts for "Admin Dinsos"
         */
        
        // Account 1
        User::create([
            'name' => 'Admin Dinsos 1',
            'email' => 'admin1@dinsos.com',
            'password' => Hash::make('password123'), // Default password
        ]);

        // Account 2
        User::create([
            'name' => 'Admin Dinsos 2',
            'email' => 'admin2@dinsos.com',
            'password' => Hash::make('password123'),
        ]);
        
        $this->command->info('2 Admin Users Created Successfully!');
    }
}
