<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Outlet;


class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalMenus = Menu::count();
        $totalEmployees = User::whereIn('role', ['penjual', 'manajer'])->count();
        $expenses = 4000000; // Example expenses

        $feedbacks = Feedback::latest()->take(10)->get();
        $employees = User::whereIn('role', ['penjual', 'manajer'])->get();

        // Grafik omzet & pesanan bulanan (12 bulan)
        $monthlyRevenue = [];
        $monthlyOrders = [];
        foreach (range(1, 12) as $month) {
            $monthlyRevenue[] = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
            $monthlyOrders[] = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $currentMonth = now()->month;
        $monthlyRevenueNow = $monthlyRevenue[$currentMonth - 1] ?? 0;
        $monthlyOrdersNow = $monthlyOrders[$currentMonth - 1] ?? 0;

        // Laporan bulanan (untuk tabel)
        $monthlyReports = [];
        foreach (range(1, 12) as $month) {
            $monthlyReports[] = [
                'month' => now()->startOfYear()->addMonths($month - 1)->format('F'),
                'orders' => $monthlyOrders[$month - 1] ?? 0,
                'revenue' => $monthlyRevenue[$month - 1] ?? 0,
                'top_menu' => '-', // Isi sesuai kebutuhan jika ada data menu terlaris per bulan
            ];
        }

        // Laporan harian (7 hari terakhir)
        $dailyReports = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyOrders = Order::whereDate('created_at', $date)->count();
            $dailyRevenue = Order::whereDate('created_at', $date)->sum('total_price');
            
            $dailyReports[] = [
                'date' => $date->format('d M Y'),
                'day' => $date->format('l'),
                'orders' => $dailyOrders,
                'revenue' => $dailyRevenue,
            ];
        }

        // Laporan tahunan (5 tahun terakhir)
        $yearlyReports = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $yearlyOrders = Order::whereYear('created_at', $year)->count();
            $yearlyRevenue = Order::whereYear('created_at', $year)->sum('total_price');
            
            $yearlyReports[] = [
                'year' => $year,
                'orders' => $yearlyOrders,
                'revenue' => $yearlyRevenue,
            ];
        }

        $activeEmployees = User::whereIn('role', ['penjual', 'manajer'])
            ->where('is_active', true)
            ->count();

    $activeOutlets = Outlet::where('is_active', true)->count();
        // Jika outlet diidentifikasi dengan model lain, sesuaikan query di atas

        // Data 3 bulan terakhir
        $recentMonths = collect(range(0, 2))->map(function ($i) {
            return now()->subMonths($i);
        })->reverse(); // urut dari terlama ke terbaru

        $recentMonthlyRevenue = [];
        $recentMonthlyOrders = [];
        $recentMonthLabels = [];

        foreach ($recentMonths as $month) {
            $recentMonthLabels[] = $month->format('F Y');
            $recentMonthlyRevenue[] = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_price');
            $recentMonthlyOrders[] = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $weeklyLabels = [];
$weeklyRevenue = [];
$weeklyOrders = [];

for ($i = 11; $i >= 0; $i--) {
    $startOfWeek = now()->subWeeks($i)->startOfWeek();
    $endOfWeek = now()->subWeeks($i)->endOfWeek();

    $weeklyLabels[] = $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M');
    $weeklyRevenue[] = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_price');
    $weeklyOrders[] = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
}

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
            'activeEmployees',
            'activeOutlets',
            'monthlyReports',
            'dailyReports',
            'yearlyReports',
            'feedbacks',
            'recentMonthlyRevenue',
            'recentMonthlyOrders',
            'recentMonthLabels',
             'weeklyLabels',
            'weeklyRevenue',
            'weeklyOrders',
            'employees'
        ));
    }
}