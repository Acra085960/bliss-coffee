<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;

class StockController extends Controller
{
    public function index()
    {
        // Ambil data stok & sisa bahan di sini
         $sellers = User::where('role', 'penjual')->with('menus')->get();
    return view('manager.stocks', compact('sellers'));

    }
}