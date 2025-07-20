<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Menu Kopi Dingin dan Stock Status:\n";
$menus = App\Models\Menu::where('category', 'Kopi Dingin')->get();
foreach($menus as $menu) {
    $availability = $menu->checkStockAvailability(1);
    echo "- {$menu->name}: Max Qty: {$availability['max_quantity']}, Status: {$availability['stock_status']}\n";
    
    if (!empty($availability['missing_ingredients'])) {
        $missing = array_column($availability['missing_ingredients'], 'name');
        echo "  Missing: " . implode(', ', $missing) . "\n";
    }
}

echo "\nBasic Ingredients Stock:\n";
$ingredients = ['Biji Kopi', 'Gula', 'Susu', 'Sirup', 'Air', 'Es Batu'];
foreach($ingredients as $name) {
    $stock = App\Models\Stock::where('name', $name)->first();
    if ($stock) {
        echo "- {$name}: {$stock->current_stock} {$stock->unit}\n";
    } else {
        echo "- {$name}: NOT FOUND\n";
    }
}

echo "\nMenu Ingredients Details:\n";
$menuWithIngredients = App\Models\Menu::where('category', 'Kopi Dingin')
    ->with(['menuIngredients.stock'])
    ->first();
    
if ($menuWithIngredients) {
    echo "Sample: {$menuWithIngredients->name}\n";
    foreach($menuWithIngredients->menuIngredients as $ingredient) {
        echo "  - {$ingredient->stock->name}: {$ingredient->quantity_needed} {$ingredient->stock->unit}\n";
    }
}
