<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if required tables exist
        if (!Schema::hasTable('orders') || !Schema::hasTable('order_items')) {
            $this->command->info('Required tables not found, skipping order seeding');
            return;
        }

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

        // Check which columns exist
        $hasOrderNumber = Schema::hasColumn('orders', 'order_number');
        $hasPaymentMethod = Schema::hasColumn('orders', 'payment_method');
        $hasPaymentStatus = Schema::hasColumn('orders', 'payment_status');

        // Create sample orders
        foreach ($customers as $customer) {
            for ($i = 0; $i < 2; $i++) {
                // Create order with available fields
                $orderData = [
                    'user_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => '08123456789',
                    'total_price' => 0,
                    'status' => collect(['pending', 'processing', 'completed'])->random(),
                    'notes' => 'Sample order ' . ($i + 1),
                ];

                // Add optional fields if columns exist
                if ($hasOrderNumber) {
                    $orderData['order_number'] = 'BC-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                }
                if ($hasPaymentMethod) {
                    $orderData['payment_method'] = collect(['cash', 'midtrans'])->random();
                }
                if ($hasPaymentStatus) {
                    $orderData['payment_status'] = collect(['pending', 'paid'])->random();
                }

                $order = Order::create($orderData);

                // Add random menu items to the order
                $selectedMenus = $menus->random(rand(1, 2));
                $totalPrice = 0;

                foreach ($selectedMenus as $menu) {
                    $quantity = rand(1, 2);
                    $price = $menu->price;
                    
                    $itemData = [
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ];

                    // Add preferences if column exists
                    if (Schema::hasColumn('order_items', 'preferences')) {
                        $itemData['preferences'] = collect([null, 'Less Sugar', 'Extra Hot', 'Oat Milk'])->random();
                    }

                    OrderItem::create($itemData);

                    $totalPrice += $price * $quantity;
                }

                // Update order total price
                $order->update(['total_price' => $totalPrice]);
            }
        }

        $this->command->info('Created sample orders for customers');
    }
}
