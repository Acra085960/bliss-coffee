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

        // Tambahkan total_sales dan average_rating ke setiap seller
        foreach ($sellers as $seller) {
            // Total penjualan (sum total_price dari semua order penjual ini)
            $seller->total_sales = $seller->orders()->sum('total_price');
            // Rata-rata rating (jika ada relasi feedbacks)
            $seller->average_rating = $seller->feedbacks()->avg('rating') ?? null;
        }

        return view('manager.performance', compact('sellers'));
    }
}