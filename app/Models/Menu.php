<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Accessor untuk image dengan default
     */
    public function getImageUrlAttribute()
    {
        // Check if menu has an assigned image
        if ($this->image && file_exists(public_path('images/' . $this->image))) {
            return asset('images/' . $this->image);
        }
        
        // Map menu names to available images
        $name = strtolower(str_replace(' ', '_', $this->name ?? ''));
        
        $imageMapping = [
            'espresso' => 'espresso.jpg',
            'americano' => 'americano.jpg',
            'cappuccino' => 'cappucino.jpeg',
            'caffe_latte' => 'caffe_latte.jpeg',
            'mocha' => 'mocha.jpg',
            'iced_americano' => 'iced_americano.jpeg',
            'iced_latte' => 'iced_latte.jpg',
            'frappuccino' => 'frappuchinno.jpg',
            'cold_brew' => 'cold_brew.jpeg',
            'hot_chocolate' => 'hot_chocolate.jpeg',
            'green_tea_latte' => 'green_tea_latte.jpg',
            'chai_tea_latte' => 'chai_tea_latte.jpg',
            'croissant_butter' => 'croissant_butter.jpg',
            'sandwich_club' => 'americano.jpg', // fallback to americano for sandwich
            'muffin_blueberry' => 'croissant_butter.jpg', // fallback to croissant for muffin
        ];
        
        // Try to find exact match first
        if (isset($imageMapping[$name])) {
            $imagePath = 'images/menu/' . $imageMapping[$name];
            if (file_exists(public_path($imagePath))) {
                return asset($imagePath);
            }
        }
        
        // Fallback logic based on name patterns
        $name = strtolower($this->name ?? '');
        if (str_contains($name, 'americano')) {
            $defaultImage = 'americano.jpg';
        } elseif (str_contains($name, 'latte')) {
            $defaultImage = 'caffe_latte.jpeg';
        } elseif (str_contains($name, 'cappuccino')) {
            $defaultImage = 'cappucino.jpeg';
        } elseif (str_contains($name, 'espresso')) {
            $defaultImage = 'espresso.jpg';
        } elseif (str_contains($name, 'mocha')) {
            $defaultImage = 'mocha.jpg';
        } elseif (str_contains($name, 'tea')) {
            $defaultImage = 'green_tea_latte.jpg';
        } elseif (str_contains($name, 'hot chocolate')) {
            $defaultImage = 'hot_chocolate.jpeg';
        } else {
            $defaultImage = 'americano.jpg'; // default fallback
        }
        
        return asset('images/menu/' . $defaultImage);
    }

    /**
     * Scope untuk menu yang tersedia saja (performance)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope untuk dashboard (minimal fields, performance)
     */
    public function scopeForDashboard($query)
    {
        return $query->select('id', 'name', 'price', 'image', 'description', 'is_available')
                    ->available()
                    ->limit(4);
    }

    // Relasi ke orders (pivot)
    public function orders()
    {
        return $this->belongsToMany(\App\Models\Order::class, 'order_items', 'menu_id', 'order_id');
    }

    // Relasi ke order details
    public function orderDetails()
    {
        return $this->hasMany(\App\Models\OrderDetail::class, 'menu_id');
    }

    public function orderItems()
{
    return $this->hasMany(\App\Models\OrderItem::class, 'menu_id');
}

    // Relasi ke penjual
    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Scope: hanya menu yang tersedia
    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }

    // Scope: stok rendah
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<', $threshold);
    }

    // Relasi ke ingredients
    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Stock::class, 'menu_ingredients', 'menu_id', 'stock_id')
                    ->withPivot('quantity_needed')
                    ->withTimestamps();
    }

    /**
     * Check if menu item can be made based on ingredient availability
     * @param int $quantity Quantity of menu items to check
     * @return array ['can_make' => boolean, 'max_quantity' => int, 'missing_ingredients' => array]
     */
    public function checkStockAvailability($quantity = 1)
    {
        // Gunakan sistem ingredient-based untuk Kopi Dingin, Kopi Panas, Non-Kopi, dan Makanan
        if (!in_array($this->category, ['Kopi Dingin', 'Kopi Panas', 'Non-Kopi', 'Makanan'])) {
            return [
                'can_make' => $this->is_available && $this->stock >= $quantity,
                'max_quantity' => $this->stock,
                'missing_ingredients' => [],
                'stock_status' => $this->stock > 0 ? 'available' : 'out_of_stock'
            ];
        }

        $ingredients = $this->menuIngredients()->with('stock')->get();
        
        if ($ingredients->isEmpty()) {
            return [
                'can_make' => $this->is_available,
                'max_quantity' => 999, // Unlimited if no ingredients defined
                'missing_ingredients' => [],
                'stock_status' => 'available'
            ];
        }

        $canMake = true;
        $maxQuantity = PHP_INT_MAX;
        $missingIngredients = [];

        foreach ($ingredients as $ingredient) {
            $stock = $ingredient->stock;
            $neededPerItem = $ingredient->quantity_needed;
            $totalNeeded = $neededPerItem * $quantity;

            if (!$stock || !$stock->is_active) {
                $canMake = false;
                $missingIngredients[] = [
                    'name' => $stock->name ?? 'Unknown',
                    'needed' => $totalNeeded,
                    'available' => 0,
                    'unit' => $stock->unit ?? 'unit'
                ];
                $maxQuantity = 0;
                continue;
            }

            if ($stock->current_stock < $totalNeeded) {
                $canMake = false;
                $missingIngredients[] = [
                    'name' => $stock->name,
                    'needed' => $totalNeeded,
                    'available' => $stock->current_stock,
                    'unit' => $stock->unit
                ];
            }

            // Calculate max quantity based on this ingredient
            if ($neededPerItem > 0) {
                $maxForThisIngredient = floor($stock->current_stock / $neededPerItem);
                $maxQuantity = min($maxQuantity, $maxForThisIngredient);
            }
        }

        // Jika ada missing ingredients, max quantity adalah 0
        if (!empty($missingIngredients)) {
            $maxQuantity = 0;
        }

        $stockStatus = 'available';
        if ($maxQuantity == 0) {
            $stockStatus = 'out_of_stock';
        } elseif ($maxQuantity <= 5) {
            $stockStatus = 'low_stock';
        }

        return [
            'can_make' => $canMake && $this->is_available,
            'max_quantity' => max(0, $maxQuantity),
            'missing_ingredients' => $missingIngredients,
            'stock_status' => $stockStatus
        ];
    }

    /**
     * Reduce stock when an order is made
     * @param int $quantity Quantity ordered
     * @return bool Success status
     */
    public function reduceStock($quantity = 1)
    {
        if (!in_array($this->category, ['Kopi Dingin', 'Kopi Panas', 'Non-Kopi', 'Makanan'])) {
            // Sistem stock lama untuk kategori lain (jika ada)
            if ($this->stock >= $quantity) {
                $this->decrement('stock', $quantity);
                return true;
            }
            return false;
        }

        $stockCheck = $this->checkStockAvailability($quantity);
        
        if (!$stockCheck['can_make']) {
            return false;
        }

        // Reduce stock for each ingredient
        $ingredients = $this->menuIngredients()->with('stock')->get();
        
        foreach ($ingredients as $ingredient) {
            $stock = $ingredient->stock;
            $totalNeeded = $ingredient->quantity_needed * $quantity;
            
            if ($stock && $stock->current_stock >= $totalNeeded) {
                $stock->decrement('current_stock', $totalNeeded);
                
                // Create stock movement record
                \App\Models\StockMovement::create([
                    'stock_id' => $stock->id,
                    'user_id' => auth()->id() ?? 1,
                    'type' => 'out',
                    'quantity' => $totalNeeded,
                    'previous_stock' => $stock->current_stock + $totalNeeded,
                    'new_stock' => $stock->current_stock,
                    'reason' => 'Order processing',
                    'notes' => "Used for {$this->name} x{$quantity}"
                ]);
            }
        }

        return true;
    }

    /**
     * Get stock status for display
     */
    public function getStockStatusAttribute()
    {
        $availability = $this->checkStockAvailability(1);
        return $availability['stock_status'];
    }

    /**
     * Get maximum available quantity
     */
    public function getMaxAvailableQuantityAttribute()
    {
        $availability = $this->checkStockAvailability(1);
        return $availability['max_quantity'];
    }
}