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
    // Hari ini
    $today = now()->toDateString();

    // Total Penjualan Hari Ini (status selesai)
    $totalSalesToday = \App\Models\Order::whereDate('created_at', $today)
        ->where('status', 'selesai')
        ->sum('total_price');

    // Total Pesanan Hari Ini (semua status)
    $totalOrdersToday = \App\Models\Order::whereDate('created_at', $today)->count();

    // Menu stok habis
    $outOfStockMenus = \App\Models\Stock::where('current_stock', 0)->count();

    // Bahan hampir habis (< 5)
    $lowStockIngredients = \App\Models\Stock::where('current_stock', '<', 5)->count();

    // Penjualan 7 hari terakhir
    $dates = collect(range(0, 6))->map(fn($i) => now()->subDays(6 - $i)->toDateString());
    $sales7Days = $dates->map(fn($date) =>
        \App\Models\Order::whereDate('created_at', $date)->where('status', 'selesai')->sum('total_price')
    );
    $orders7Days = $dates->map(fn($date) =>
        \App\Models\Order::whereDate('created_at', $date)->count()
    );

    // Menu terlaris minggu ini (pie chart)
    $startOfWeek = now()->startOfWeek();
    $topMenus = \App\Models\Menu::withCount(['orders' => function($q) use ($startOfWeek) {
    $q->where('orders.created_at', '>=', $startOfWeek);
}])->orderByDesc('orders_count')->take(5)->get();

    // Tabel stok menu
    $menus = \App\Models\Menu::all();

    // Tabel bahan baku
    $ingredients = \App\Models\Stock::all();

    // Kinerja penjual minggu ini
    $sellerPerformance = \App\Models\User::where('role', 'penjual')
        ->withCount(['orders' => function($q) use ($startOfWeek) {
            $q->where('orders.created_at', '>=', $startOfWeek)->where('status', 'selesai');
        }])->get();

    // Laporan singkat
    $totalRevenueWeek = \App\Models\Order::where('created_at', '>=', $startOfWeek)
        ->where('status', 'selesai')->sum('total_price');
$days = now()->diffInDays($startOfWeek->copy()->addWeek());
$avgOrderPerDay = $days > 0 ? (\App\Models\Order::where('created_at', '>=', $startOfWeek)->count() / $days) : 0;

    return view('manager.dashboard', compact(
        'totalSalesToday', 'totalOrdersToday', 'outOfStockMenus', 'lowStockIngredients',
        'dates', 'sales7Days', 'orders7Days', 'topMenus', 'menus', 'ingredients',
        'sellerPerformance', 'totalRevenueWeek', 'avgOrderPerDay'
    ));
}
}