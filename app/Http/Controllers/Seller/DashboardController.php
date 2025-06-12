<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $menuCount = Menu::count();

        return view('penjual.dashboard', compact(
            'pendingOrders',
            'processingOrders', 
            'completedOrders',
            'menuCount'
        ));
        $totalOrders = \App\Models\Order::count();
        $activeMenus = \App\Models\Menu::count();
        $stockSum = \App\Models\Stock::sum('quantity');
        $isStockLow = $stockSum < 20;

        return view('admin.dashboard', compact('totalOrders', 'activeMenus', 'stockSum', 'isStockLow'));
    }
}
