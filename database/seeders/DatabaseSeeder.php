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
    // Owner
    User::create([
        'name' => 'Owner Bliss',
        'email' => 'owner@bliss.com',
        'password' => Hash::make('password'),
        'role' => 'owner',
        'email_verified_at' => now(), // <-- tambahkan ini
    ]);

    // Manajer
    User::create([
        'name' => 'Manajer Bliss',
        'email' => 'manajer@bliss.com',
        'password' => Hash::make('password'),
        'role' => 'manajer',
        'email_verified_at' => now(), // <-- tambahkan ini
    ]);

    // Penjual
    User::create([
        'name' => 'Penjual Bliss',
        'email' => 'penjual@bliss.com',
        'password' => Hash::make('password'),
        'role' => 'penjual',
        'email_verified_at' => now(), // <-- tambahkan ini
    ]);

    // Pembeli
    User::create([
        'name' => 'Pembeli Bliss',
        'email' => 'pembeli@bliss.com',
        'password' => Hash::make('password'),
        'role' => 'pembeli',
        'email_verified_at' => now(), // <-- tambahkan ini
    ]);
}
}
