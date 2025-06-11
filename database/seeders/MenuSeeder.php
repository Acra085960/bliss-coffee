<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

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
            'name' => 'Espresso',
            'description' => 'Kopi espresso original dengan cita rasa yang kuat',
            'price' => 15000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Cappuccino',
            'description' => 'Espresso dengan steamed milk dan foam yang lembut',
            'price' => 25000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Latte',
            'description' => 'Espresso dengan steamed milk yang creamy',
            'price' => 28000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Americano',
            'description' => 'Espresso dengan air panas, rasa kopi yang bold',
            'price' => 18000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Mocha',
            'description' => 'Espresso dengan cokelat dan steamed milk',
            'price' => 32000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Frappuccino',
            'description' => 'Minuman kopi dingin dengan es dan whipped cream',
            'price' => 35000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Macchiato',
            'description' => 'Espresso dengan sedikit steamed milk',
            'price' => 22000,
            'is_available' => true,
        ]);
        Menu::create([
            'name' => 'Affogato',
            'description' => 'Espresso shot dengan vanilla ice cream',
            'price' => 30000,
            'is_available' => true,
        ]);

        // Anda bisa menambahkan lebih banyak data menu sesuai kebutuhan
    }
}
