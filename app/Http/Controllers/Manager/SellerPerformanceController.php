<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellerPerformanceController extends Controller
{
    public function index()
    {
        // Ambil semua penjual beserta jumlah pesanan
        $sellers = User::where('role', 'penjual')
            ->withCount('orders')
            ->get();

        foreach ($sellers as $seller) {
            $seller->total_sales = $seller->orders()->sum('total_price');
        }

        return view('manager.performance', compact('sellers'));
    }
}