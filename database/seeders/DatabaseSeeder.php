<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing users safely
        User::query()->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create test users with simple passwords for testing
        $users = [
            [
                'name' => 'Owner Bliss',
                'email' => 'owner@bliss.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manajer Bliss',
                'email' => 'manajer@bliss.com',
                'password' => Hash::make('password'),
                'role' => 'manajer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Penjual Bliss',
                'email' => 'penjual@bliss.com',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pembeli Bliss',
                'email' => 'pembeli@bliss.com',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole($userData['role']);
            $this->command->info("Created user: {$userData['email']} with role: {$userData['role']}");
        }

        // Output credentials for easy testing
        $this->command->info('=== Login Credentials ===');
        $this->command->info('Owner: owner@bliss.com / password');
        $this->command->info('Manajer: manajer@bliss.com / password');
        $this->command->info('Penjual: penjual@bliss.com / password');
        $this->command->info('Pembeli: pembeli@bliss.com / password');

        // Create additional test customer accounts to demonstrate registration
        $additionalCustomers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($additionalCustomers as $customerData) {
            $customer = User::create($customerData);
            $customer->assignRole($customerData['role']);
            $this->command->info("Created customer: {$customerData['email']} with role: {$customerData['role']}");
        }

        // Call other seeders in correct order
        $this->call([
            OutletSeeder::class,
            MenuSeeder::class,
            StockSeeder::class,
            CoffeeIngredientsSeeder::class,
            OrderSeeder::class,
        ]);
    }
}

