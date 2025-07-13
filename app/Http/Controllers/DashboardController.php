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
                return redirect()->route('manager.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'pembeli':
                return redirect()->route('customer.dashboard');
            default:
                return abort(403, 'Role tidak valid'); // Jika role tidak ditemukan
        }
    }

}
