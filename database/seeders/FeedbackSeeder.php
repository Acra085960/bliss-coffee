<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // Get completed orders
        $completedOrders = Order::where('status', 'completed')->get();
        
        if ($completedOrders->isEmpty()) {
            $this->command->info('No completed orders found, skipping feedback seeding');
            return;
        }

        // Add feedback for some completed orders
        foreach ($completedOrders->take(3) as $order) {
            Feedback::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'rating' => rand(4, 5), // Good ratings
                'comment' => collect([
                    'Pelayanan sangat memuaskan!',
                    'Kopi yang sangat enak, akan pesan lagi.',
                    'Pengalaman yang luar biasa, terima kasih!',
                    'Kualitas kopi sangat baik.',
                    'Pelayanan cepat dan ramah.'
                ])->random()
            ]);
        }

        $this->command->info('Created sample feedback data');
    }
}
