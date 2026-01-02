<?php

namespace Database\Seeders;

// We use the full path in the code below, so we don't strictly need these 'use' lines,
// but it's good practice to keep them clean.
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Train;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@email.com',
            'role' => 'admin',
            'password' => bcrypt('password123'), 
        ]);

        // 2. Create Train
        Train::create([
            'name' => 'Express 99',
            'destination' => 'Cyberpunk City',
            'departure_time' => now()->addDays(2),
            'seats_available' => 50,
        ]);
    }
}