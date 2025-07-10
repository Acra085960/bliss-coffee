<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Menu;

class UpdateOrderItemsMenuData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-menu-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing order items with menu name and image data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update order items with menu data...');
        
        // Get all order items that don't have menu_name filled
        $orderItems = OrderItem::whereNull('menu_name')->with('menu')->get();
        
        $this->info("Found {$orderItems->count()} order items to update");
        
        $bar = $this->output->createProgressBar($orderItems->count());
        $bar->start();
        
        $updated = 0;
        
        foreach ($orderItems as $item) {
            if ($item->menu) {
                $item->update([
                    'menu_name' => $item->menu->name,
                    'menu_image' => $item->menu->image
                ]);
                $updated++;
            } else {
                // If menu is deleted, try to get a fallback name
                $item->update([
                    'menu_name' => 'Menu tidak tersedia',
                    'menu_image' => null
                ]);
                $updated++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully updated {$updated} order items");
        
        return 0;
    }
}
