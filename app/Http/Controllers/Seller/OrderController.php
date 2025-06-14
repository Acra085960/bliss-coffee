<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $query = Order::with(['orderItems.menu', 'user'])
                     ->whereDate('created_at', $date);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->orderByRaw("FIELD(status, 'pending', 'processing', 'ready', 'completed', 'cancelled')")
                       ->orderBy('created_at', 'asc')
                       ->paginate(20);
        
        // Get counts for each status
        $statusCounts = [
            'pending' => Order::whereDate('created_at', $date)->where('status', 'pending')->count(),
            'processing' => Order::whereDate('created_at', $date)->where('status', 'processing')->count(),
            'ready' => Order::whereDate('created_at', $date)->where('status', 'ready')->count(),
            'completed' => Order::whereDate('created_at', $date)->where('status', 'completed')->count(),
            'cancelled' => Order::whereDate('created_at', $date)->where('status', 'cancelled')->count(),
        ];
        
        return view('penjual.orders.index', compact('orders', 'statusCounts', 'status', 'date'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.menu', 'user']);
        return view('penjual.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        $statusMessages = [
            'processing' => 'Pesanan sedang diproses',
            'ready' => 'Pesanan siap diambil',
            'completed' => 'Pesanan selesai',
            'cancelled' => 'Pesanan dibatalkan'
        ];

        return response()->json([
            'success' => true,
            'message' => $statusMessages[$request->status] ?? 'Status berhasil diperbarui',
            'new_status' => $request->status
        ]);
    }

    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'status' => 'required|in:processing,ready,completed,cancelled'
        ]);

        Order::whereIn('id', $request->order_ids)->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui secara batch'
        ]);
    }
}
