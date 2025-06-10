<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data dummy ke tabel menus
        Menu::create([
            'name' => 'Cappuccino',
            'description' => 'Cappuccino coffee with rich flavor.',
            'price' => 25.00,
            'stock' => 100,
        ]);

        Menu::create([
            'name' => 'Latte',
            'description' => 'Smooth latte with creamy foam.',
            'price' => 30.00,
            'stock' => 50,
        ]);

        // Anda bisa menambahkan lebih banyak data menu sesuai kebutuhan
    }
}
