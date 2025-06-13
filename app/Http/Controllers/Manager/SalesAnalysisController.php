<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class SalesAnalysisController extends Controller
{
    public function index()
    {
        // Penjualan hari ini
        $today = Carbon::today();
        $todaySales = Order::whereDate('created_at', $today)->sum('total_price');
        $todayOrders = Order::whereDate('created_at', $today)->count();

        // Penjualan minggu ini
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $weekSales = Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_price');
        $weekOrders = Order::whereBetween('created_at', [$weekStart, $weekEnd])->count();

        // Data untuk grafik penjualan 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Order::whereDate('created_at', $date)->sum('total_price');
        }

        // Penjualan terbaru (5 terakhir)
        $recentSales = Order::with('user')->latest()->take(5)->get();

        return view('manager.sales', compact(
            'todaySales', 'weekSales', 'todayOrders', 'weekOrders',
            'recentSales', 'chartLabels', 'chartData'
        ));
    }
}