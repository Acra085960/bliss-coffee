<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== STOCK MANAGEMENT SYSTEM TEST ===\n\n";

// Test 1: Check initial stock status
echo "1. INITIAL STOCK STATUS:\n";
$kopiDinginMenus = App\Models\Menu::where('category', 'Kopi Dingin')->get();
foreach($kopiDinginMenus as $menu) {
    $availability = $menu->checkStockAvailability(1);
    echo "   - {$menu->name}: Max {$availability['max_quantity']} porsi, Status: {$availability['stock_status']}\n";
}

// Test 2: Simulate large order to test stock depletion
echo "\n2. SIMULATE LARGE ORDER (50 Frappuccino):\n";
$frappuccino = App\Models\Menu::where('name', 'Frappuccino')->where('category', 'Kopi Dingin')->first();
if ($frappuccino) {
    $largeOrderCheck = $frappuccino->checkStockAvailability(50);
    echo "   - Can make 50 Frappuccino: " . ($largeOrderCheck['can_make'] ? 'YES' : 'NO') . "\n";
    echo "   - Max available: {$largeOrderCheck['max_quantity']} porsi\n";
    
    if (!empty($largeOrderCheck['missing_ingredients'])) {
        echo "   - Missing ingredients:\n";
        foreach($largeOrderCheck['missing_ingredients'] as $missing) {
            echo "     * {$missing['name']}: need {$missing['needed']}, available {$missing['available']} {$missing['unit']}\n";
        }
    }
}

// Test 3: Reduce stock manually to create shortage
echo "\n3. CREATE STOCK SHORTAGE (Reduce Gula to 0.1 kg):\n";
$gula = App\Models\Stock::where('name', 'Gula')->first();
if ($gula) {
    $oldStock = $gula->current_stock;
    $gula->update(['current_stock' => 0.1]);
    
    // Test Frappuccino again
    $shortageCheck = $frappuccino->checkStockAvailability(10);
    echo "   - Can make 10 Frappuccino after sugar shortage: " . ($shortageCheck['can_make'] ? 'YES' : 'NO') . "\n";
    echo "   - Max available: {$shortageCheck['max_quantity']} porsi\n";
    
    if (!empty($shortageCheck['missing_ingredients'])) {
        echo "   - Missing ingredients:\n";
        foreach($shortageCheck['missing_ingredients'] as $missing) {
            echo "     * {$missing['name']}: need {$missing['needed']}, available {$missing['available']} {$missing['unit']}\n";
        }
    }
    
    // Restore stock
    $gula->update(['current_stock' => $oldStock]);
    echo "   - Stock restored to {$oldStock} kg\n";
}

// Test 4: Test stock reduction
echo "\n4. TEST STOCK REDUCTION:\n";
$icedLatte = App\Models\Menu::where('name', 'Iced Latte')->where('category', 'Kopi Dingin')->first();
if ($icedLatte) {
    echo "   - Testing Iced Latte stock reduction for 3 portions\n";
    
    // Get ingredients before
    $beforeIngredients = [];
    foreach($icedLatte->menuIngredients as $ingredient) {
        $beforeIngredients[$ingredient->stock->name] = $ingredient->stock->current_stock;
    }
    
    // Reduce stock
    $success = $icedLatte->reduceStock(3);
    echo "   - Reduction success: " . ($success ? 'YES' : 'NO') . "\n";
    
    if ($success) {
        echo "   - Stock changes:\n";
        foreach($icedLatte->fresh()->menuIngredients as $ingredient) {
            $before = $beforeIngredients[$ingredient->stock->name];
            $after = $ingredient->stock->fresh()->current_stock;
            $used = $before - $after;
            echo "     * {$ingredient->stock->name}: {$before} -> {$after} ({$used} used)\n";
        }
    }
}

// Test 5: Check non-cold coffee menu (should use old system)
echo "\n5. NON-COLD COFFEE MENU TEST:\n";
$hotCoffee = App\Models\Menu::where('category', 'Kopi Panas')->first();
if ($hotCoffee) {
    $hotCheck = $hotCoffee->checkStockAvailability(1);
    echo "   - {$hotCoffee->name}: Max {$hotCheck['max_quantity']} porsi, Status: {$hotCheck['stock_status']}\n";
    echo "   - Uses old stock system: " . ($hotCoffee->category !== 'Kopi Dingin' ? 'YES' : 'NO') . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
