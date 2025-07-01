<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $status = $request->get('status');
        
        $query = Stock::where('is_active', true);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($status) {
            switch ($status) {
                case 'low':
                    $query->whereRaw('current_stock <= minimum_stock');
                    break;
                case 'out':
                    $query->where('current_stock', '<=', 0);
                    break;
                case 'overstock':
                    $query->whereRaw('current_stock >= maximum_stock');
                    break;
            }
        }
        
        $stocks = $query->orderBy('name')->paginate(20);
        
        // Get summary data
        $summary = [
            'total_items' => Stock::where('is_active', true)->count(),
            'low_stock' => Stock::where('is_active', true)->whereRaw('current_stock <= minimum_stock')->count(),
            'out_of_stock' => Stock::where('is_active', true)->where('current_stock', '<=', 0)->count(),
            'categories' => Stock::where('is_active', true)->distinct()->pluck('category')
        ];
        
        // Get recent movements
        $recentMovements = StockMovement::with(['stock', 'user'])
                                       ->orderBy('created_at', 'desc')
                                       ->limit(10)
                                       ->get();
        
        return view('penjual.stock.index', compact('stocks', 'summary', 'recentMovements', 'category', 'status'));
    }

    public function create()
    {
        $categories = ['Kopi', 'Susu & Dairy', 'Gula & Pemanis', 'Bahan Tambahan', 'Kemasan', 'Lainnya'];
        $units = ['kg', 'liter', 'pcs', 'gram', 'ml', 'sachet', 'cup'];
        
        return view('penjual.stock.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'maximum_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $stock = Stock::create($request->all());

        // Create initial stock movement
        StockMovement::create([
            'stock_id' => $stock->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $request->current_stock,
            'previous_stock' => 0,
            'new_stock' => $request->current_stock,
            'reason' => 'Initial stock',
            'notes' => 'Stock item created'
        ]);

        return redirect()->route('penjual.stock.index')->with('success', 'Item stok berhasil ditambahkan!');
    }

    public function edit(Stock $stock)
    {
        $categories = ['Kopi', 'Susu & Dairy', 'Gula & Pemanis', 'Bahan Tambahan', 'Kemasan', 'Lainnya'];
        $units = ['kg', 'liter', 'pcs', 'gram', 'ml', 'sachet', 'cup'];
        
        return view('penjual.stock.edit', compact('stock', 'categories', 'units'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'minimum_stock' => 'required|numeric|min:0',
            'maximum_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $stock->update($request->except('current_stock')); // Don't update current_stock directly

        return redirect()->route('penjual.stock.index')->with('success', 'Item stok berhasil diperbarui!');
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $stock = Stock::findOrFail($request->stock_id);
            $previousStock = $stock->current_stock;
            
            switch ($request->type) {
                case 'in':
                    $newStock = $previousStock + $request->quantity;
                    break;
                case 'out':
                    $newStock = max(0, $previousStock - $request->quantity);
                    break;
                case 'adjustment':
                    $newStock = $request->quantity;
                    break;
            }

            // Update stock
            $stock->update(['current_stock' => $newStock]);

            // Create movement record
            StockMovement::create([
                'stock_id' => $stock->id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => $request->quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $request->reason,
                'notes' => $request->notes
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Stok berhasil diperbarui!']);
    }

    public function movements(Stock $stock)
    {
        $movements = $stock->movements()
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        
    }

    public function lowStock()
    {
        $lowStocks = Stock::where('is_active', true)
                         ->whereRaw('current_stock <= minimum_stock')
                         ->orderBy('current_stock', 'asc')
                         ->get();
        
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.stock_id' => 'required|exists:stocks,id',
            'updates.*.quantity' => 'required|numeric',
            'updates.*.type' => 'required|in:in,out,adjustment',
            'reason' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->updates as $update) {
                $stock = Stock::findOrFail($update['stock_id']);
                $previousStock = $stock->current_stock;
                
                switch ($update['type']) {
                    case 'in':
                        $newStock = $previousStock + $update['quantity'];
                        break;
                    case 'out':
                        $newStock = max(0, $previousStock - $update['quantity']);
                        break;
                    case 'adjustment':
                        $newStock = $update['quantity'];
                        break;
                }

                $stock->update(['current_stock' => $newStock]);

                StockMovement::create([
                    'stock_id' => $stock->id,
                    'user_id' => auth()->id(),
                    'type' => $update['type'],
                    'quantity' => $update['quantity'],
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reason' => $request->reason,
                    'notes' => 'Bulk update'
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Bulk update berhasil!']);
    }
}
