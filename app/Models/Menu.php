<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model
    protected $table = 'menus';  // Ganti dengan nama tabel Anda jika berbeda

    // Tentukan kolom yang dapat diisi massal (mass assignable)
    protected $fillable = [
        'name',         // Nama menu
        'description',  // Deskripsi menu
        'price',        // Harga menu
        'stock',        // Jumlah stok menu
    ];

    // Relasi: Satu menu bisa ada di banyak detail pesanan
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
