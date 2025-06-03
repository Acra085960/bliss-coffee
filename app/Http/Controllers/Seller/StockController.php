<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::all();
        return view('admin.stock', compact('stocks'));
    }

    public function update(Request $request)
    {
        foreach ($request->input('stock', []) as $id => $value) {
            Stock::where('id', $id)->update(['quantity' => $value]);
        }

        return back()->with('success', 'Stok berhasil diperbarui.');
    }
}
