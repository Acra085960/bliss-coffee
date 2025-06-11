<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get customer users
        $customers = User::where('role', 'pembeli')->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('No customers found, skipping order seeding');
            return;
        }

        $menus = Menu::all();
        if ($menus->isEmpty()) {
            $this->command->info('No menus found, skipping order seeding');
            return;
        }

        // Create sample orders
        foreach ($customers as $customer) {
            for ($i = 0; $i < 3; $i++) {
                $order = Order::create([
                    'user_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => '08123456789',
                    'total_price' => 0, // Will be calculated
                    'status' => collect(['pending', 'processing', 'completed'])->random(),
                    'notes' => 'Sample order ' . ($i + 1),
                ]);

                // Add random menu items to the order
                $selectedMenus = $menus->random(rand(1, 3));
                $totalPrice = 0;

                foreach ($selectedMenus as $menu) {
                    $quantity = rand(1, 3);
                    $price = $menu->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalPrice += $price * $quantity;
                }

                // Update order total price
                $order->update(['total_price' => $totalPrice]);
            }
        }

        $this->command->info('Created sample orders for customers');
    }
}
