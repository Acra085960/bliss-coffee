<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'province', 'lat', 'lng', 'phone', 'employee_id'
    ];


 public function user()
{
    return $this->belongsTo(User::class);
}

public function orders()
{
    return $this->hasMany(\App\Models\Order::class);
}

public function stocks()
{
    return $this->hasMany(\App\Models\Stock::class);
}
}