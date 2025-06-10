<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model
    protected $table = 'orders';  // Ganti dengan nama tabel Anda jika berbeda

    // Tentukan kolom yang dapat diisi massal (mass assignable)
    protected $fillable = [
        'user_id',           // ID user yang membuat pesanan
        'total_price',       // Total harga pesanan
        'status',            // Status pesanan (pending, completed, dll.)
    ];

    // Relasi: Satu order bisa memiliki banyak detail pesanan (misalnya, produk atau menu yang dipesan)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
