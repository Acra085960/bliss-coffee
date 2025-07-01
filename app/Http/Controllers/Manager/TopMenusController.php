<?php


namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class TopMenusController extends Controller
{
    public function index()
{
    $topMenus = Menu::select('menus.*')
        ->with('seller') // relasi ke penjual
        ->withCount(['orderItems as sold_count' => function($q) {
            $q->select(DB::raw('SUM(quantity)'));
        }])
        ->withSum(['orderItems as total_income' => function($q) {
            $q->select(DB::raw('SUM(quantity * price)'));
        }], 'total_income')
        ->orderByDesc('sold_count')
        ->take(10)
        ->get();

    return view('manager.topmenus', compact('topMenus'));
}
}