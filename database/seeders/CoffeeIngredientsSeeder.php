<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Menu;
use App\Models\MenuIngredient;
use App\Models\User;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class CoffeeIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first outlet for default assignment
        $firstOutlet = \App\Models\Outlet::first();
        
        // 9 bahan dasar untuk kopi dan non-kopi
        $basicIngredients = [
            [
                'name' => 'Biji Kopi',
                'category' => 'Bahan Utama',
                'current_stock' => 5.0,
                'minimum_stock' => 1.0,
                'maximum_stock' => 20.0,
                'unit' => 'kg',
                'price_per_unit' => 150000,
                'description' => 'Biji kopi untuk semua jenis kopi',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Gula',
                'category' => 'Bahan Tambahan',
                'current_stock' => 3.0,
                'minimum_stock' => 0.5,
                'maximum_stock' => 10.0,
                'unit' => 'kg',
                'price_per_unit' => 15000,
                'description' => 'Gula putih untuk pemanis',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Susu',
                'category' => 'Bahan Tambahan',
                'current_stock' => 8.0,
                'minimum_stock' => 2.0,
                'maximum_stock' => 20.0,
                'unit' => 'liter',
                'price_per_unit' => 18000,
                'description' => 'Susu segar untuk latte dan cappuccino',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Sirup',
                'category' => 'Bahan Tambahan',
                'current_stock' => 2.5,
                'minimum_stock' => 0.5,
                'maximum_stock' => 10.0,
                'unit' => 'liter',
                'price_per_unit' => 25000,
                'description' => 'Sirup berbagai rasa untuk varian kopi',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Air',
                'category' => 'Bahan Utama',
                'current_stock' => 50.0,
                'minimum_stock' => 10.0,
                'maximum_stock' => 100.0,
                'unit' => 'liter',
                'price_per_unit' => 500,
                'description' => 'Air bersih untuk brewing kopi',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Es Batu',
                'category' => 'Bahan Tambahan',
                'current_stock' => 10.0,
                'minimum_stock' => 2.0,
                'maximum_stock' => 30.0,
                'unit' => 'kg',
                'price_per_unit' => 3000,
                'description' => 'Es batu untuk minuman dingin',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Bubuk Teh',
                'category' => 'Bahan Utama',
                'current_stock' => 2.0,
                'minimum_stock' => 0.5,
                'maximum_stock' => 10.0,
                'unit' => 'kg',
                'price_per_unit' => 80000,
                'description' => 'Bubuk teh hitam untuk teh dan chai',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Bubuk Teh Hijau',
                'category' => 'Bahan Utama',
                'current_stock' => 1.5,
                'minimum_stock' => 0.3,
                'maximum_stock' => 8.0,
                'unit' => 'kg',
                'price_per_unit' => 120000,
                'description' => 'Bubuk teh hijau matcha premium',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Bubuk Coklat',
                'category' => 'Bahan Utama',
                'current_stock' => 3.0,
                'minimum_stock' => 0.8,
                'maximum_stock' => 15.0,
                'unit' => 'kg',
                'price_per_unit' => 95000,
                'description' => 'Bubuk coklat premium untuk hot chocolate',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Sandwich Club',
                'category' => 'Bahan Makanan',
                'current_stock' => 15.0,
                'minimum_stock' => 1.0,
                'maximum_stock' => 25.0,
                'unit' => 'kg',
                'price_per_unit' => 12000,
                'description' => 'Sandwich dengan isi daging ayam',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Blueberry Muffin',
                'category' => 'Bahan Makanan',
                'current_stock' => 15.0,
                'minimum_stock' => 1.0,
                'maximum_stock' => 25.0,
                'unit' => 'kg',
                'price_per_unit' => 25000,
                'description' => 'Muffin isi blueberry',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
            [
                'name' => 'Butter Croissant',
                'category' => 'Bahan Makanan',
                'current_stock' => 15.0,
                'minimum_stock' => 1.0,
                'maximum_stock' => 25.0,
                'unit' => 'kg',
                'price_per_unit' => 45000,
                'description' => 'Croissant dengan isi butter',
                'is_active' => true,
                'outlet_id' => $firstOutlet?->id ?? 1,
            ],
        ];

        $ingredientIds = [];
        
        // Create atau update ingredients
        foreach ($basicIngredients as $ingredient) {
            $stock = Stock::updateOrCreate(
                [
                    'name' => $ingredient['name'],
                    'outlet_id' => $ingredient['outlet_id']
                ],
                $ingredient
            );
            $ingredientIds[$ingredient['name']] = $stock->id;

            // Create initial stock movement
            $seller = User::where('role', 'penjual')->first();
            if ($seller) {
                StockMovement::updateOrCreate([
                    'stock_id' => $stock->id,
                    'user_id' => $seller->id,
                    'type' => 'in',
                    'reason' => 'Initial stock for coffee ingredients'
                ], [
                    'quantity' => $stock->current_stock,
                    'previous_stock' => 0,
                    'new_stock' => $stock->current_stock,
                    'notes' => 'Stock item created for coffee ingredients system'
                ]);
            }
        }

        // Recipe untuk setiap menu Kopi Dingin (dalam gram/ml per porsi)
        $coldCoffeeRecipes = [
            'Iced Americano' => [
                'Biji Kopi' => 0.018,     // 18 gram
                'Air' => 0.2,             // 200 ml
                'Es Batu' => 0.1,         // 100 gram
            ],
            'Iced Latte' => [
                'Biji Kopi' => 0.018,     // 18 gram
                'Susu' => 0.15,           // 150 ml
                'Air' => 0.05,            // 50 ml
                'Es Batu' => 0.1,         // 100 gram
            ],
            'Frappuccino' => [
                'Biji Kopi' => 0.018,     // 18 gram
                'Susu' => 0.2,            // 200 ml
                'Gula' => 0.015,          // 15 gram
                'Es Batu' => 0.15,        // 150 gram
                'Sirup' => 0.03,          // 30 ml
            ],
            'Iced Cappuccino' => [
                'Biji Kopi' => 0.018,     // 18 gram
                'Susu' => 0.12,           // 120 ml
                'Air' => 0.08,            // 80 ml
                'Es Batu' => 0.1,         // 100 gram
            ],
            'Cold Brew' => [
                'Biji Kopi' => 0.025,     // 25 gram (lebih banyak untuk cold brew)
                'Air' => 0.25,            // 250 ml
                'Es Batu' => 0.08,        // 80 gram
            ],
        ];

        // Recipe untuk setiap menu Makanan (dalam gram/ml per porsi)
        $foodRecipes = [
            'Muffin Blueberry' => [
                'Blueberry Muffin' => 1.00,    
            ],
            'Croissant Butter' => [
                'Butter Croissant' => 1.00,    
            ],
            'Sandwich Club' => [
                'Sandwich Club' => 1.00,       
            ],
        ];

        // Recipe untuk setiap menu Non-Kopi (dalam gram/ml per porsi)
        $nonCoffeeRecipes = [
            'Hot Chocolate' => [
                'Bubuk Coklat' => 0.025,   // 25 gram
                'Susu' => 0.2,             // 200 ml
                'Gula' => 0.01,            // 10 gram
                'Air' => 0.05,             // 50 ml
            ],
            'Green Tea Latte' => [
                'Bubuk Teh Hijau' => 0.008, // 8 gram
                'Susu' => 0.18,            // 180 ml
                'Gula' => 0.008,           // 8 gram
                'Air' => 0.07,             // 70 ml
            ],
            'Chai Tea Latte' => [
                'Bubuk Teh' => 0.012,      // 12 gram
                'Susu' => 0.2,             // 200 ml
                'Gula' => 0.012,           // 12 gram
                'Sirup' => 0.015,          // 15 ml (spice syrup)
                'Air' => 0.05,             // 50 ml
            ],
        ];

        // Recipe untuk setiap menu Kopi Panas (dalam gram/ml per porsi)
        $hotCoffeeRecipes = [
            'Espresso' => [
                'Biji Kopi' => 0.015,     // 15 gram
                'Air' => 0.03,            // 30 ml (espresso shot)
            ],
            'Americano' => [
                'Biji Kopi' => 0.015,     // 15 gram
                'Air' => 0.18,            // 180 ml
            ],
            'Cappuccino' => [
                'Biji Kopi' => 0.015,     // 15 gram
                'Susu' => 0.12,           // 120 ml
                'Air' => 0.03,            // 30 ml
            ],
            'Caffe Latte' => [
                'Biji Kopi' => 0.015,     // 15 gram
                'Susu' => 0.18,           // 180 ml
                'Air' => 0.03,            // 30 ml
            ],
            'Mocha' => [
                'Biji Kopi' => 0.015,     // 15 gram
                'Susu' => 0.15,           // 150 ml
                'Sirup' => 0.025,         // 25 ml (chocolate syrup)
                'Air' => 0.03,            // 30 ml
            ],
        ];

        // Link recipes to cold coffee menus
        foreach ($coldCoffeeRecipes as $menuName => $recipe) {
            $menu = Menu::where('name', $menuName)->where('category', 'Kopi Dingin')->first();
            
            if ($menu) {
                // Hapus ingredient yang sudah ada untuk menu ini
                MenuIngredient::where('menu_id', $menu->id)->delete();
                
                // Tambahkan ingredient baru
                foreach ($recipe as $ingredientName => $quantity) {
                    if (isset($ingredientIds[$ingredientName])) {
                        MenuIngredient::create([
                            'menu_id' => $menu->id,
                            'stock_id' => $ingredientIds[$ingredientName],
                            'quantity_needed' => $quantity,
                        ]);
                    }
                }
                
                $this->command->info("Added recipe for: {$menuName}");
            }
        }

        // Link recipes to hot coffee menus
        foreach ($hotCoffeeRecipes as $menuName => $recipe) {
            $menu = Menu::where('name', $menuName)->where('category', 'Kopi Panas')->first();
            
            if ($menu) {
                // Hapus ingredient yang sudah ada untuk menu ini
                MenuIngredient::where('menu_id', $menu->id)->delete();
                
                // Tambahkan ingredient baru
                foreach ($recipe as $ingredientName => $quantity) {
                    if (isset($ingredientIds[$ingredientName])) {
                        MenuIngredient::create([
                            'menu_id' => $menu->id,
                            'stock_id' => $ingredientIds[$ingredientName],
                            'quantity_needed' => $quantity,
                        ]);
                    }
                }
                
                $this->command->info("Added recipe for: {$menuName}");
            }
        }

        // Link recipes to non-coffee menus
        foreach ($nonCoffeeRecipes as $menuName => $recipe) {
            $menu = Menu::where('name', $menuName)->where('category', 'Non-Kopi')->first();
            
            if ($menu) {
                // Hapus ingredient yang sudah ada untuk menu ini
                MenuIngredient::where('menu_id', $menu->id)->delete();
                
                // Tambahkan ingredient baru
                foreach ($recipe as $ingredientName => $quantity) {
                    if (isset($ingredientIds[$ingredientName])) {
                        MenuIngredient::create([
                            'menu_id' => $menu->id,
                            'stock_id' => $ingredientIds[$ingredientName],
                            'quantity_needed' => $quantity,
                        ]);
                    }
                }
                
                $this->command->info("Added recipe for: {$menuName}");
            }
        }

        // Link recipes to food menus
        foreach ($foodRecipes as $menuName => $recipe) {
            $menu = Menu::where('name', $menuName)->where('category', 'Makanan')->first();
            
            if ($menu) {
                // Hapus ingredient yang sudah ada untuk menu ini
                MenuIngredient::where('menu_id', $menu->id)->delete();
                
                // Tambahkan ingredient baru
                foreach ($recipe as $ingredientName => $quantity) {
                    if (isset($ingredientIds[$ingredientName])) {
                        MenuIngredient::create([
                            'menu_id' => $menu->id,
                            'stock_id' => $ingredientIds[$ingredientName],
                            'quantity_needed' => $quantity,
                        ]);
                    }
                }
                
                $this->command->info("Added recipe for: {$menuName}");
            }
        }

        $this->command->info('Coffee, non-coffee, and food ingredients and recipes seeded successfully!');
    }
}
