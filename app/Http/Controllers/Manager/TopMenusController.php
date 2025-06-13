<?php


namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class TopMenusController extends Controller
{
    public function index()
    {
        // Ambil 10 menu terlaris berdasarkan jumlah terjual (dari order_details)
        $topMenus = Menu::with('seller')
            ->withSum('orderDetails as sold_count', 'quantity')
            ->get();

        // Hitung total pendapatan manual (quantity * price dari order_details)
        foreach ($topMenus as $menu) {
            $menu->total_income = $menu->orderDetails->sum(function($detail) {
                return $detail->quantity * $detail->price;
            });
        }

        // Urutkan dan ambil 10 teratas
        $topMenus = $topMenus->sortByDesc('sold_count')->take(10);

        return view('manager.topmenus', compact('topMenus'));
    }
}