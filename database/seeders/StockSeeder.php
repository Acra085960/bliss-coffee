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
