<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,canceled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('penjual.orders.index')->with('success', 'Status pesanan diperbarui.');
    }
}
