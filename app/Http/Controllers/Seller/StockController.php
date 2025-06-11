<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::all(); // Use Stock model here
        return view('admin.stock.stock', compact('stocks'));
    }

    public function create()
    {
        return view('admin.stock.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Create the stock item
        \App\Models\Stock::create($validated);

        // Redirect back with a success message
        return redirect()->route('penjual.stock.index')->with('success', 'Stock berhasil ditambahkan!');
    }

    public function edit(Stock $stock)
    {
        return view('admin.stock.edit', compact('stock'));
    }

}
