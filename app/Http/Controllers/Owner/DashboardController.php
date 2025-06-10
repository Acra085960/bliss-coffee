<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalMenus = Menu::count();
        $totalEmployees = User::whereIn('role', ['penjual', 'manajer'])->count();
        $expenses = 4000000; // Example expenses

        return view('owner.dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalMenus', 
            'totalEmployees',
            'expenses'
        ));
    }
}
