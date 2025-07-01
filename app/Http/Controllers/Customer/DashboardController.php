<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();
        
        $totalOrders = \App\Models\Order::where('user_id', auth()->id())->count();
$totalSpent = \App\Models\Order::where('user_id', auth()->id())->sum('total_price');
        
        // Add menu data for display
        $menus = Menu::limit(6)->get();

        return view('customer.dashboard', compact(
            'recentOrders',
            'totalOrders',
            'totalSpent',
            'menus'
        ));
    }
}
