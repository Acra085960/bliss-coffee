<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit',
        'price_per_unit',
        'description',
        'is_active',
        'outlet_id'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function menuIngredients()
    {
        return $this->hasMany(MenuIngredient::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        } elseif ($this->current_stock >= $this->maximum_stock) {
            return 'overstock';
        }
        return 'normal';
    }

    public function getStockPercentageAttribute()
    {
        if ($this->maximum_stock <= 0) return 0;
        return ($this->current_stock / $this->maximum_stock) * 100;
    }

    public function outlet()
{
    return $this->belongsTo(\App\Models\Outlet::class);
}
}
