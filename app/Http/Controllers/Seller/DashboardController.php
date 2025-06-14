<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Daily Statistics
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::whereDate('created_at', $today)->where('status', 'pending')->count();
        $processingOrders = Order::whereDate('created_at', $today)->where('status', 'processing')->count();
        $completedOrders = Order::whereDate('created_at', $today)->where('status', 'completed')->count();
        $cancelledOrders = Order::whereDate('created_at', $today)->where('status', 'cancelled')->count();
        
        // Revenue Statistics
        $todayRevenue = Order::whereDate('created_at', $today)->sum('total_price');
        $avgOrderValue = $todayOrders > 0 ? $todayRevenue / $todayOrders : 0;
        
        // Popular Menu Items Today
        $popularMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->whereDate('orders.created_at', $today)
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        // Hourly Order Distribution
        $hourlyOrders = Order::whereDate('created_at', $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();
        
        // Recent Orders
        $recentOrders = Order::with(['orderItems.menu', 'user'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Weekly Comparison
        $lastWeek = Carbon::now()->subWeek();
        $weeklyOrders = Order::whereBetween('created_at', [$lastWeek, Carbon::now()])->count();
        $weeklyRevenue = Order::whereBetween('created_at', [$lastWeek, Carbon::now()])->sum('total_price');
        
        return view('penjual.dashboard', compact(
            'todayOrders',
            'pendingOrders',
            'processingOrders', 
            'completedOrders',
            'cancelledOrders',
            'todayRevenue',
            'avgOrderValue',
            'popularMenus',
            'hourlyOrders',
            'recentOrders',
            'weeklyOrders',
            'weeklyRevenue'
        ));
    }
}
