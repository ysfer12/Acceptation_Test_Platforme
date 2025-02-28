<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff User',
            'first_name' => 'Staff',
            'last_name' => 'User',
            'email' => 'staff@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => 'active',
        ]);
    }
}
