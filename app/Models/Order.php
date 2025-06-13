<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'payment_status',
    ];

    // Relasi ke user (pembeli)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Relasi ke menu (many-to-many)
    public function menus()
    {
        return $this->belongsToMany(\App\Models\Menu::class, 'order_menu', 'order_id', 'menu_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function orderDetails()
{
    return $this->hasMany(\App\Models\OrderDetail::class);
}
}