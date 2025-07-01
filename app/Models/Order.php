<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Customer\Outlet;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'order_number',
        'notes',
        'outlet_id'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Relasi ke user (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke menu (jika masih pakai pivot order_menu)
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'order_menu', 'order_id', 'menu_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Relasi ke order details (jika pakai tabel order_details)
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Relasi ke feedback
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    // Cek apakah order memiliki umpan balik
    public function hasFeedback()
    {
        return $this->feedback()->exists();
    }


public function outlet()
{
    return $this->belongsTo(\App\Models\Outlet::class, 'outlet_id');
}
}