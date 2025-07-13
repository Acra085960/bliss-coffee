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
    public function run(): void
    {
        $menus = [
            // Kopi Panas
            [
                'name' => 'Espresso',
                'description' => 'Kopi espresso murni dengan rasa yang kuat dan aroma yang khas',
                'price' => 15000,
                'category' => 'Kopi Panas',
                'image' => 'menu/espresso.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Americano',
                'description' => 'Espresso dengan air panas, memberikan rasa kopi yang bold namun ringan',
                'price' => 18000,
                'category' => 'Kopi Panas',
                'image' => 'menu/americano.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso dengan steamed milk dan foam yang lembut, balance sempurna',
                'price' => 25000,
                'category' => 'Kopi Panas',
                'image' => 'menu/cappucino.jpeg',
                'is_available' => true,
            ],
            [
                'name' => 'Caffe Latte',
                'description' => 'Espresso dengan steamed milk yang creamy dan lembut',
                'price' => 28000,
                'category' => 'Kopi Panas',
                'image' => 'menu/caffe_latte.jpeg',
                'is_available' => true,
            ],
            [
                'name' => 'Mocha',
                'description' => 'Perpaduan sempurna espresso, cokelat, dan steamed milk',
                'price' => 32000,
                'category' => 'Kopi Panas',
                'image' => 'menu/mocha.jpg',
                'is_available' => true,
            ],
            
            // Kopi Dingin
            [
                'name' => 'Iced Americano',
                'description' => 'Espresso dengan air dingin dan es batu, menyegarkan dan bold',
                'price' => 20000,
                'category' => 'Kopi Dingin',
                'image' => 'menu/iced_americano.jpeg',
                'is_available' => true,
            ],
            [
                'name' => 'Iced Latte',
                'description' => 'Espresso dengan cold milk dan es, creamy dan segar',
                'price' => 30000,
                'category' => 'Kopi Dingin',
                'image' => 'menu/iced_latte.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Frappuccino',
                'description' => 'Minuman kopi dingin blended dengan es dan whipped cream',
                'price' => 35000,
                'category' => 'Kopi Dingin',
                'image' => 'menu/frappuchinno.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Cold Brew',
                'description' => 'Kopi yang diseduh dingin selama 12 jam, smooth dan less acidic',
                'price' => 25000,
                'category' => 'Kopi Dingin',
                'image' => 'menu/cold_brew.jpeg',
                'is_available' => true,
            ],
            
            // Non-Kopi
            [
                'name' => 'Hot Chocolate',
                'description' => 'Cokelat panas premium dengan whipped cream dan marshmallow',
                'price' => 22000,
                'category' => 'Non-Kopi',
                'image' => 'menu/hot_chocolate.jpeg',
                'is_available' => true,
            ],
            [
                'name' => 'Green Tea Latte',
                'description' => 'Matcha premium dengan steamed milk, creamy dan healthy',
                'price' => 26000,
                'category' => 'Non-Kopi',
                'image' => 'menu/green_tea_latte.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Chai Tea Latte',
                'description' => 'Teh rempah India dengan steamed milk dan madu',
                'price' => 24000,
                'category' => 'Non-Kopi',
                'image' => 'menu/chai_tea_latte.jpg',
                'is_available' => true,
            ],
            
            // Makanan
            [
                'name' => 'Croissant Butter',
                'description' => 'Croissant segar dengan butter premium, renyah dan buttery',
                'price' => 18000,
                'category' => 'Makanan',
                'image' => 'menu/croissant_butter.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Sandwich Club',
                'description' => 'Sandwich dengan ayam, bacon, lettuce, dan tomato',
                'price' => 35000,
                'category' => 'Makanan',
                'image' => 'menu/americano.jpg', // placeholder, bisa diganti dengan sandwich image
                'is_available' => true,
            ],
            [
                'name' => 'Muffin Blueberry',
                'description' => 'Muffin lembut dengan blueberry segar, perfect untuk snacking',
                'price' => 15000,
                'category' => 'Makanan',
                'image' => 'menu/americano.jpg', // placeholder, bisa diganti dengan muffin image
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Created ' . count($menus) . ' menu items across multiple categories');
    }
}
