<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Stock;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $activeMenus = Menu::where('is_active', true)->count();
        $lowStocks = Stock::where('quantity', '<', 10)->count(); // contoh threshold

        $latestOrders = Order::latest()->take(5)->get();
        $topMenus = Menu::withCount('orders')->orderByDesc('orders_count')->take(5)->get();

        return view('manager.dashboard', compact(
            'totalOrders', 'activeMenus', 'lowStocks', 'latestOrders', 'topMenus'
        ));
    }
}