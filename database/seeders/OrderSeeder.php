<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data dummy ke tabel orders
        Order::create([
            'user_id' => 1,       // ID pengguna
            'total_price' => 100.50,
            'status' => 'pending',
        ]);

        Order::create([
            'user_id' => 2,       // ID pengguna lain
            'total_price' => 250.00,
            'status' => 'completed',
        ]);

        // Anda bisa menambahkan lebih banyak data sesuai kebutuhan
    }
}
