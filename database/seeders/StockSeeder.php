<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $outlets = Outlet::all();

    // Pastikan jumlah outlet cukup
    if ($outlets->count() < 6) {
        $this->command->error('Minimal harus ada 6 outlet di tabel outlets sebelum menjalankan StockSeeder.');
        return;
    }
    
        $stocks = [
            [
                'name' => 'Kopi Arabica',
                'category' => 'Kopi',
                'current_stock' => 5.5,
                'minimum_stock' => 2.0,
                'maximum_stock' => 20.0,
                'unit' => 'kg',
                'price_per_unit' => 150000,
                'description' => 'Biji kopi arabica premium',
                'is_active' => true,
                'outlet_id' => $outlets[0]->id ?? null, 
            ],
            [
                'name' => 'Kopi Robusta',
                'category' => 'Kopi',
                'current_stock' => 3.2,
                'minimum_stock' => 1.5,
                'maximum_stock' => 15.0,
                'unit' => 'kg',
                'price_per_unit' => 120000,
                'description' => 'Biji kopi robusta lokal',
                'is_active' => true,
                'outlet_id' => $outlets[1]->id ?? null, // gunakan id outlet pertama
            ],
            [
                'name' => 'Susu Full Cream',
                'category' => 'Susu & Dairy',
                'current_stock' => 8.0,
                'minimum_stock' => 3.0,
                'maximum_stock' => 30.0,
                'unit' => 'liter',
                'price_per_unit' => 18000,
                'description' => 'Susu segar full cream',
                'is_active' => true,
                'outlet_id' => $outlets[2]->id ?? null, // gunakan id outlet pertama
            ],
            [
                'name' => 'Gula Pasir',
                'category' => 'Gula & Pemanis',
                'current_stock' => 2.5,
                'minimum_stock' => 1.0,
                'maximum_stock' => 10.0,
                'unit' => 'kg',
                'price_per_unit' => 15000,
                'description' => 'Gula pasir putih',
                'is_active' => true,
                'outlet_id' => $outlets[3]->id ?? null, // gunakan id outlet pertama
            ],
            [
                'name' => 'Cup Paper 8oz',
                'category' => 'Kemasan',
                'current_stock' => 250,
                'minimum_stock' => 100,
                'maximum_stock' => 1000,
                'unit' => 'pcs',
                'price_per_unit' => 300,
                'description' => 'Cup kertas ukuran 8oz',
                'is_active' => true,
                'outlet_id' => $outlets[4]->id ?? null, // gunakan id outlet pertama
            ],
            [
                'name' => 'Whipped Cream',
                'category' => 'Bahan Tambahan',
                'current_stock' => 1.2,
                'minimum_stock' => 0.5,
                'maximum_stock' => 5.0,
                'unit' => 'liter',
                'price_per_unit' => 45000,
                'description' => 'Krim kocok untuk topping',
                'is_active' => true,
                'outlet_id' => $outlets[5]->id ?? null, // gunakan id outlet pertama
            ]
        ];

        foreach ($stocks as $stockData) {
            $stock = Stock::create($stockData);

            // Create initial stock movement if there's a seller user
            $seller = User::where('role', 'penjual')->first();
            if ($seller) {
                StockMovement::create([
                    'stock_id' => $stock->id,
                    'user_id' => $seller->id,
                    'type' => 'in',
                    'quantity' => $stockData['current_stock'],
                    'previous_stock' => 0,
                    'new_stock' => $stockData['current_stock'],
                    'reason' => 'Initial stock',
                    'notes' => 'Stock item created with initial quantity'
                ]);
            }
        }

        $this->command->info('Created ' . count($stocks) . ' stock items');
    }
}
