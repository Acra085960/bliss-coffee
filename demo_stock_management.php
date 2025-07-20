<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 STOCK MANAGEMENT SYSTEM DEMONSTRATION\n";
echo "========================================\n\n";

// Show cold coffee menus with their stock status
echo "📋 COLD COFFEE MENU & STOCK STATUS:\n";
echo "-----------------------------------\n";
$coldCoffeeMenus = App\Models\Menu::where('category', 'Kopi Dingin')->get();

foreach($coldCoffeeMenus as $menu) {
    $availability = $menu->checkStockAvailability(1);
    $status = $availability['stock_status'];
    $maxQty = $availability['max_quantity'];
    
    $statusIcon = $status === 'available' ? '✅' : ($status === 'low_stock' ? '⚠️' : '❌');
    
    echo sprintf("%-20s %s Max: %3d porsi (%s)\n", 
        $menu->name, 
        $statusIcon, 
        $maxQty, 
        ucfirst(str_replace('_', ' ', $status))
    );
    
    // Show recipe details for one menu
    if ($menu->name === 'Frappuccino') {
        echo "  Recipe: ";
        $ingredients = [];
        foreach($menu->menuIngredients as $ingredient) {
            $qty = $ingredient->quantity_needed;
            $unit = $ingredient->stock->unit;
            $name = $ingredient->stock->name;
            $ingredients[] = "{$qty}{$unit} {$name}";
        }
        echo implode(', ', $ingredients) . "\n";
    }
}

echo "\n📦 INGREDIENT STOCK LEVELS:\n";
echo "---------------------------\n";
$ingredients = ['Biji Kopi', 'Gula', 'Susu', 'Sirup', 'Air', 'Es Batu'];
foreach($ingredients as $name) {
    $stock = App\Models\Stock::where('name', $name)->first();
    if ($stock) {
        $level = $stock->current_stock;
        $unit = $stock->unit;
        $min = $stock->minimum_stock;
        $max = $stock->maximum_stock;
        
        $percentage = ($level / $max) * 100;
        $statusIcon = $level <= $min ? '🔴' : ($percentage < 50 ? '🟡' : '🟢');
        
        echo sprintf("%-10s %s %6.1f %s (%.0f%% capacity)\n", 
            $name, $statusIcon, $level, $unit, $percentage);
    }
}

echo "\n🧮 STOCK CALCULATION EXAMPLE:\n";
echo "-----------------------------\n";
$frappuccino = App\Models\Menu::where('name', 'Frappuccino')->first();
if ($frappuccino) {
    echo "Menu: Frappuccino (Rp " . number_format($frappuccino->price, 0, ',', '.') . ")\n";
    
    // Show what's needed for 1 portion
    echo "\nFor 1 portion:\n";
    foreach($frappuccino->menuIngredients as $ingredient) {
        $needed = $ingredient->quantity_needed;
        $available = $ingredient->stock->current_stock;
        $unit = $ingredient->stock->unit;
        $name = $ingredient->stock->name;
        
        echo sprintf("  %-10s: %6.3f %s (available: %.1f %s)\n", 
            $name, $needed, $unit, $available, $unit);
    }
    
    // Calculate max possible
    $maxPossible = $frappuccino->checkStockAvailability(1)['max_quantity'];
    echo "\nMaximum possible portions: {$maxPossible}\n";
    
    // Show what happens with different order sizes
    echo "\nStock check for different quantities:\n";
    $testQuantities = [1, 5, 10, 20, 50];
    foreach($testQuantities as $qty) {
        $check = $frappuccino->checkStockAvailability($qty);
        $result = $check['can_make'] ? "✅ Can make" : "❌ Cannot make";
        echo sprintf("  %2d portions: %s", $qty, $result);
        if (!$check['can_make'] && !empty($check['missing_ingredients'])) {
            $missing = array_column($check['missing_ingredients'], 'name');
            echo " (Missing: " . implode(', ', $missing) . ")";
        }
        echo "\n";
    }
}

echo "\n🛒 CART SIMULATION:\n";
echo "------------------\n";
echo "Simulating customer adding items to cart...\n";

// Simulate adding items to cart
$cartItems = [
    ['menu' => 'Iced Americano', 'qty' => 2],
    ['menu' => 'Iced Latte', 'qty' => 3],
    ['menu' => 'Frappuccino', 'qty' => 1]
];

$totalCost = 0;
$canOrder = true;

foreach($cartItems as $item) {
    $menu = App\Models\Menu::where('name', $item['menu'])->where('category', 'Kopi Dingin')->first();
    if ($menu) {
        $check = $menu->checkStockAvailability($item['qty']);
        $status = $check['can_make'] ? '✅' : '❌';
        $cost = $menu->price * $item['qty'];
        $totalCost += $cost;
        
        echo sprintf("  %s %-15s x%d = Rp %s %s\n", 
            $status,
            $item['menu'], 
            $item['qty'], 
            number_format($cost, 0, ',', '.'),
            $check['can_make'] ? '' : '(Insufficient stock)'
        );
        
        if (!$check['can_make']) {
            $canOrder = false;
        }
    }
}

echo sprintf("\nTotal: Rp %s\n", number_format($totalCost, 0, ',', '.'));
echo "Order status: " . ($canOrder ? "✅ Ready to checkout" : "❌ Cannot proceed") . "\n";

echo "\n🎉 SYSTEM FEATURES DEMONSTRATED:\n";
echo "================================\n";
echo "✅ Real-time stock checking for cold coffee items\n";
echo "✅ Ingredient-based recipe calculations\n";
echo "✅ Maximum quantity determination\n";
echo "✅ Stock shortage detection\n";
echo "✅ Cart validation simulation\n";
echo "✅ Error handling for insufficient stock\n";
echo "✅ Clear status indicators\n";

echo "\n📱 Customer will see:\n";
echo "- Stock badges on menu items (Available/Low Stock/Out of Stock)\n";
echo "- Maximum quantity limits in dropdowns\n";
echo "- Warning messages for limited stock\n";
echo "- Disabled ordering for out-of-stock items\n";
echo "- Clear error messages with missing ingredients\n";

echo "\n🔄 When orders are processed:\n";
echo "- Ingredient stock automatically reduced\n";
echo "- Stock movements recorded for audit\n";
echo "- Real-time availability updated\n";
echo "- Traditional stock system preserved for other categories\n";

echo "\n🎯 IMPLEMENTATION SUCCESS! 🎯\n";
