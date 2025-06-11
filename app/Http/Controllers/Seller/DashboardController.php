<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = \App\Models\Order::count();
        $activeMenus = \App\Models\Menu::count();
        $stockSum = \App\Models\Stock::sum('quantity');
        $isStockLow = $stockSum < 20;

        return view('admin.dashboard', compact('totalOrders', 'activeMenus', 'stockSum', 'isStockLow'));
    }
}
