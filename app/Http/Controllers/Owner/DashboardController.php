<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\Feedback;
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
        $feedbacks = Feedback::latest()->take(10)->get(); // atau sesuaikan query sesuai kebutuhan
        $employees = User::whereIn('role', ['penjual', 'manajer'])->get();

        // Grafik omzet & pesanan bulanan
        $currentMonth = now()->month;
        $monthlyRevenueNow = $monthlyRevenue[$currentMonth - 1] ?? 0;
        $monthlyOrdersNow = $monthlyOrders[$currentMonth - 1] ?? 0;
        foreach (range(1, 12) as $month) {
            $monthlyRevenue[] = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
            $monthlyOrders[] = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $monthlyReports = [];
foreach (range(1, 12) as $month) {
    $monthlyReports[] = [
        'month' => now()->startOfYear()->addMonths($month - 1)->format('F'),
        'orders' => $monthlyOrders[$month - 1] ?? 0,
        'revenue' => $monthlyRevenue[$month - 1] ?? 0,
        'top_menu' => '-', // Isi sesuai kebutuhan jika ada data menu terlaris per bulan
    ];

        $activeEmployees = User::whereIn('role', ['penjual', 'manajer'])
    ->where('is_active', true)
    ->count();


    $activeOutlets = \App\Models\User::where('role', 'outlet')->where('is_active', true)->count();
    // atau jika outlet diidentifikasi dengan cara lain, sesuaikan query-nya

      return view('owner.dashboard', compact(
    'totalRevenue', 
    'totalOrders', 
    'totalMenus', 
    'totalEmployees',
    'expenses',
    'monthlyRevenue',
    'monthlyOrders',
    'monthlyRevenueNow',
    'monthlyOrdersNow',
    'activeEmployees' ,
    'activeOutlets' ,
     'monthlyReports' ,
    'feedbacks' ,
    'employees'
));
    }
}
}
