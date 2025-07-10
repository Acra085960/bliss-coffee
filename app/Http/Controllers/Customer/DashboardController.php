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
        
        // Optimized queries dengan select field minimal dan limit
        $recentOrders = Order::select('id', 'total_price', 'status', 'created_at')
                           ->where('user_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->limit(3) // Kurangi dari 5 ke 3 untuk loading cepat
                           ->get();
        
        // Optimized aggregation queries
        $userStats = Order::where('user_id', $user->id)
                        ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(total_price), 0) as total_spent')
                        ->first();
        
        // Optimized menu query - menggunakan scope baru untuk performance
        $menus = Menu::forDashboard()->get();

        return view('customer.dashboard', [
            'recentOrders' => $recentOrders,
            'totalOrders' => $userStats->total_orders ?? 0,
            'totalSpent' => $userStats->total_spent ?? 0,
            'menus' => $menus
        ]);
    }
}
