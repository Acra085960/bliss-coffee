<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

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
        'image',        // Gambar menu
        'is_available'  // Ketersediaan menu
    ];

    // Tentukan tipe data untuk kolom tertentu
    protected $casts = [
        'price' => 'decimal:2',    // Mengatur kolom price sebagai decimal dengan 2 angka di belakang koma
        'is_available' => 'boolean', // Mengatur kolom is_available sebagai boolean
    ];

    // Relasi: Satu menu bisa ada di banyak detail pesanan
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
