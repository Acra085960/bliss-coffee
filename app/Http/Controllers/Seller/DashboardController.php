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
    }
}
