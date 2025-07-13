<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->bootStrap();

$menus = \App\Models\Menu::select('id', 'name', 'image')->limit(5)->get();

echo "Menu Data:\n";
foreach ($menus as $menu) {
    echo "ID: {$menu->id}, Name: {$menu->name}, Image: " . ($menu->image ?? 'NULL') . "\n";
}
