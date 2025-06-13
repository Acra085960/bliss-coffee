<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellerPerformanceController extends Controller
{
    public function index()
    {
        // Ambil semua penjual beserta jumlah pesanan dan total penjualan
        $sellers = User::where('role', 'penjual')
            ->withCount(['orders'])
            ->with(['orders' => function($q) {
                $q->select('user_id', DB::raw('SUM(total_price) as total_sales'))
                  ->groupBy('user_id');
            }])
            ->get();

        // Tambahkan total_sales dan average_rating ke setiap seller
        foreach ($sellers as $seller) {
            $seller->total_sales = $seller->orders->sum('total_price');
            // Jika ada fitur rating, misal relasi: $seller->feedbacks()->avg('rating')
            $seller->average_rating = $seller->feedbacks()->avg('rating') ?? null;
        }

        return view('manager.performance', compact('sellers'));
    }
}