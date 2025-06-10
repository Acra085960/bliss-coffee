<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::sum('total_price');
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalMenus = Menu::count();

        return view('manajer.dashboard', compact(
            'totalSales', 
            'completedOrders', 
            'pendingOrders',
            'totalMenus'
        ));
    }
}
