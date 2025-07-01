<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;

class StockController extends Controller
{
        public function index()
            {
                $allSellers = \App\Models\User::where('role', 'penjual')->get();
                $selectedSellerId = request('seller_id');
                $sellers = \App\Models\User::where('role', 'penjual')
                    ->when($selectedSellerId, fn($q) => $q->where('id', $selectedSellerId))
                    ->with('stocks', 'outlets')
                    ->get();

                return view('manager.stocks', compact('sellers', 'allSellers', 'selectedSellerId'));
            }
            public function update(Request $request, $stockId)
{
    $request->validate([
        'current_stock' => 'required|numeric|min:0'
    ]);
    $stock = \App\Models\Stock::findOrFail($stockId);
    $stock->current_stock = $request->current_stock;
    $stock->save();

    return back()->with('success', 'Stok berhasil diperbarui.');
}
}