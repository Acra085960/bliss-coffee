<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description', // add this if you use description
    ];

    public function orders()
{
    return $this->belongsToMany(\App\Models\Order::class, 'order_menu', 'menu_id', 'order_id');
}

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<', $threshold);
    }

    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function orderDetails()
{
    return $this->hasMany(\App\Models\OrderDetail::class, 'menu_id');
}
}

