<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'province', 'lat', 'lng', 'phone', 'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function orders()
{
    return $this->hasMany(\App\Models\Order::class);
}
}