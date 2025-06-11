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
        'customer_name',     // Nama pelanggan
        'customer_phone',    // Nomor telepon pelanggan
        'total_price',       // Total harga pesanan
        'status',            // Status pesanan (pending, completed, dll.)
        'notes'              // Catatan tambahan untuk pesanan
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Relasi: Satu order dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu order bisa memiliki banyak item pesanan (misalnya, produk atau menu yang dipesan)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Satu order bisa memiliki satu umpan balik (feedback)
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    // Cek apakah order memiliki umpan balik
    public function hasFeedback()
    {
        return $this->feedback()->exists();
    }
}
