<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Outlet;

class DashboardController extends Controller
{
      public function index()
{
    $today = Carbon::today();
    $outlet = Outlet::where('user_id', auth()->id())->first();

    // Jika tidak ada outlet, semua statistik 0
    if (!$outlet) {
        $todayOrders = $pendingOrders = $processingOrders = $completedOrders = $cancelledOrders = 0;
        $todayRevenue = $avgOrderValue = $weeklyOrders = $weeklyRevenue = 0;
        $popularMenus = $hourlyOrders = $recentOrders = collect();
    } else {
        $todayOrders = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->where('status', 'pending')->count();
        $processingOrders = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->where('status', 'processing')->count();
        $completedOrders = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->where('status', 'completed')->count();
        $cancelledOrders = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->where('status', 'cancelled')->count();

        $todayRevenue = Order::where('outlet_id', $outlet->id)->whereDate('created_at', $today)->sum('total_price');
        $avgOrderValue = $todayOrders > 0 ? $todayRevenue / $todayOrders : 0;

        $popularMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('orders.outlet_id', $outlet->id)
            ->whereDate('orders.created_at', $today)
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        $hourlyOrders = Order::where('outlet_id', $outlet->id)
            ->whereDate('created_at', $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();

        $recentOrders = Order::with(['orderItems.menu', 'user'])
            ->where('outlet_id', $outlet->id)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $lastWeek = Carbon::now()->subWeek();
        $weeklyOrders = Order::where('outlet_id', $outlet->id)
            ->whereBetween('created_at', [$lastWeek, Carbon::now()])->count();
        $weeklyRevenue = Order::where('outlet_id', $outlet->id)
            ->whereBetween('created_at', [$lastWeek, Carbon::now()])->sum('total_price');
    }

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
