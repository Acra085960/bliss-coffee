<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with('orderItems.menu')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('customer.orders', compact('orders'));
    }
}
