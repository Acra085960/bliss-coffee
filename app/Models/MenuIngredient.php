<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'stock_id',
        'quantity_needed'
    ];

    protected $casts = [
        'quantity_needed' => 'decimal:2',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
