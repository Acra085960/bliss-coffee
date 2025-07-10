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
        if ($this->image && file_exists(public_path('images/menu/' . $this->image))) {
            return asset('images/menu/' . $this->image);
        }
        
        // Default image berdasarkan kategori atau nama menu
        $defaultImages = [
            'coffee' => 'latte.jpg',
            'tea' => 'green_tea.jpg', 
            'food' => 'sandwich.jpg',
            'dessert' => 'cheesecake.jpg'
        ];
        
        $category = strtolower($this->category ?? '');
        $name = strtolower($this->name ?? '');
        
        // Pilih default image berdasarkan kategori atau nama
        if (str_contains($name, 'coffee') || str_contains($name, 'espresso') || str_contains($name, 'latte')) {
            $defaultImage = 'latte.jpg';
        } elseif (str_contains($name, 'tea')) {
            $defaultImage = 'green_tea.jpg';
        } elseif (str_contains($name, 'sandwich') || str_contains($name, 'burger')) {
            $defaultImage = 'sandwich.jpg';
        } else {
            $defaultImage = 'latte.jpg'; // default fallback
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