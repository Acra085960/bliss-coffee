<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function orders()
{
    return $this->belongsToMany(Order::class, 'order_items');
}
}
