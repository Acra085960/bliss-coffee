<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Memeriksa apakah pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login');  // Jika pengguna belum login, redirect ke login
        }

        $user = auth()->user();
        
        // Memastikan pengguna memiliki role yang ditugaskan di Spatie Permission
        if (!$user->hasAnyRole(['pembeli', 'penjual', 'manajer', 'owner'])) {
            $user->assignRole($user->role);
        }

        // Mendapatkan role dari Spatie Permission
        $role = $user->getRoleNames()->first();

        // Mengarahkan ke dashboard berdasarkan role
        switch ($role) {
            case 'penjual':
                return redirect()->route('penjual.dashboard');
            case 'manajer':
                return redirect()->route('manajer.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'pembeli':
                return redirect()->route('customer.dashboard');
            default:
                return abort(403, 'Role tidak valid'); // Jika role tidak ditemukan
        }
    }

    // Dashboard untuk Penjual
    protected function penjualDashboard()
    {
        $orderCount = Order::where('status', 'pending')->count(); // Menghitung pesanan pending
        $menuCount = Menu::count();  // Menghitung jumlah menu yang ada

        return view('penjual.dashboard', compact('orderCount', 'menuCount'));
    }

    // Dashboard untuk Manajer
    protected function manajerDashboard()
    {
        $totalSales = Order::sum('total_price'); // Total penjualan
        $completedOrders = Order::where('status', 'completed')->count(); // Jumlah pesanan selesai

        return view('manajer.dashboard', compact('totalSales', 'completedOrders'));
    }

    // Dashboard untuk Owner
    protected function ownerDashboard()
    {
        $totalRevenue = Order::sum('total_price');  // Total pendapatan
        $expenses = 4000000; // Pengeluaran (contoh, sesuaikan dengan data Anda)

        return view('owner.dashboard', compact('totalRevenue', 'expenses'));
    }

    // Dashboard untuk Pembeli
    protected function pembeliDashboard()
    {
        $orderStatus = 'Pesanan Anda sedang diproses'; // Status pesanan terakhir

        return view('customer.dashboard', compact('orderStatus'));
    }
}
