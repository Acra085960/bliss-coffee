<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, call the RolesSeeder to create roles
        $this->call([
            RolesSeeder::class
        ]);

        // Clear existing users to avoid conflicts
        User::truncate();

        // Owner
        $owner = User::create([
            'name' => 'Owner Bliss',
            'email' => 'owner@bliss.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);
        $owner->assignRole('owner');

        // Manajer
        $manajer = User::create([
            'name' => 'Manajer Bliss',
            'email' => 'manajer@bliss.com',
            'password' => Hash::make('password'),
            'role' => 'manajer',
            'email_verified_at' => now(),
        ]);
        $manajer->assignRole('manajer');

        // Penjual
        $penjual = User::create([
            'name' => 'Penjual Bliss',
            'email' => 'penjual@bliss.com',
            'password' => Hash::make('password'),
            'role' => 'penjual',
            'email_verified_at' => now(),
        ]);
        $penjual->assignRole('penjual');

        // Pembeli
        $pembeli = User::create([
            'name' => 'Pembeli Bliss',
            'email' => 'pembeli@bliss.com',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
            'email_verified_at' => now(),
        ]);
        $pembeli->assignRole('pembeli');

        // Output for verification
        $this->command->info('Users created:');
        $this->command->info('Owner: owner@bliss.com - password');
        $this->command->info('Manajer: manajer@bliss.com - password');
        $this->command->info('Penjual: penjual@bliss.com - password');
        $this->command->info('Pembeli: pembeli@bliss.com - password');

        // Call other seeders
        $this->call([
            OrderSeeder::class,
            MenuSeeder::class,
        ]);
    }
}

