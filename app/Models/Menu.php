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
}