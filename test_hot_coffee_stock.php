<?php

require 'vendor/autoload.php';

use App\Models\Menu;
use App\Models\Stock;
use App\Models\MenuIngredient;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST STOCK MANAGEMENT KOPI PANAS ===\n\n";

// 1. Cek menu kopi panas yang ada
echo "1. Menu Kopi Panas yang tersedia:\n";
$hotCoffeeMenus = Menu::where('category', 'Kopi Panas')->get(['id', 'name', 'category']);
foreach ($hotCoffeeMenus->take(5) as $menu) {
    echo "   - {$menu->name} (ID: {$menu->id})\n";
}
echo "\n";

// 2. Cek bahan baku yang tersedia
echo "2. Stok Bahan Baku:\n";
$ingredients = ['Biji Kopi', 'Susu', 'Air', 'Sirup', 'Gula', 'Es Batu'];
foreach ($ingredients as $ingredient) {
    $stock = Stock::where('name', $ingredient)->first();
    if ($stock) {
        echo "   - {$ingredient}: {$stock->current_stock} {$stock->unit}\n";
    } else {
        echo "   - {$ingredient}: TIDAK DITEMUKAN\n";
    }
}
echo "\n";

// 3. Test availability check untuk menu hot coffee
echo "3. Test Stock Availability untuk Kopi Panas:\n";
$testMenus = ['Espresso', 'Americano', 'Cappuccino', 'Caffe Latte', 'Mocha'];

foreach ($testMenus as $menuName) {
    $menu = Menu::where('name', $menuName)->where('category', 'Kopi Panas')->first();
    if ($menu) {
        echo "   {$menuName}:\n";
        
        // Cek resep
        $ingredients = MenuIngredient::where('menu_id', $menu->id)->with('stock')->get();
        if ($ingredients->count() > 0) {
            echo "     Resep:\n";
            foreach ($ingredients as $ingredient) {
                $stock = $ingredient->stock;
                echo "       - {$stock->name}: {$ingredient->quantity_needed} {$stock->unit}\n";
            }
            
            // Test availability
            $availability = $menu->checkStockAvailability(1);
            echo "     Status: " . ($availability['can_make'] ? 'BISA DIBUAT' : 'TIDAK BISA DIBUAT') . "\n";
            echo "     Max Quantity: {$availability['max_quantity']}\n";
            echo "     Stock Status: {$availability['stock_status']}\n";
            
            if (!$availability['can_make'] && !empty($availability['missing_ingredients'])) {
                echo "     Bahan kurang:\n";
                foreach ($availability['missing_ingredients'] as $missing) {
                    echo "       - {$missing['name']}: butuh {$missing['needed']}, tersedia {$missing['available']} {$missing['unit']}\n";
                }
            }
        } else {
            echo "     TIDAK ADA RESEP TERDAFTAR!\n";
        }
        echo "\n";
    }
}

// 4. Test stock reduction
echo "4. Test Stock Reduction:\n";
$testMenu = Menu::where('name', 'Americano')->where('category', 'Kopi Panas')->first();
if ($testMenu) {
    echo "   Testing dengan menu: {$testMenu->name}\n";
    
    // Ambil stok awal
    $beforeStock = [];
    $ingredients = MenuIngredient::where('menu_id', $testMenu->id)->with('stock')->get();
    foreach ($ingredients as $ingredient) {
        $beforeStock[$ingredient->stock->name] = $ingredient->stock->current_stock;
    }
    
    echo "   Stok sebelum:\n";
    foreach ($beforeStock as $name => $stock) {
        echo "     - {$name}: {$stock}\n";
    }
    
    // Test reduce stock
    $reduceResult = $testMenu->reduceStock(1);
    echo "   Reduce Stock Result: " . ($reduceResult ? 'SUCCESS' : 'FAILED') . "\n";
    
    if ($reduceResult) {
        echo "   Stok sesudah:\n";
        foreach ($ingredients as $ingredient) {
            $ingredient->stock->refresh();
            $name = $ingredient->stock->name;
            $newStock = $ingredient->stock->current_stock;
            $used = $beforeStock[$name] - $newStock;
            echo "     - {$name}: {$newStock} (digunakan: {$used})\n";
        }
    }
    echo "\n";
}

echo "=== TEST SELESAI ===\n";
