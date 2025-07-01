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