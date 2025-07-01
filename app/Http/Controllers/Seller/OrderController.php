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

    // Ambil semua outlet milik penjual yang login
    $outletIds = \App\Models\Outlet::where('user_id', auth()->id())->pluck('id');

    $query = Order::with(['orderItems.menu', 'user'])
                 ->whereIn('outlet_id', $outletIds)
                 ->whereDate('created_at', $date);

    if ($status !== 'all') {
        $query->where('status', $status);
    }

    $orders = $query->orderByRaw("FIELD(status, 'pending', 'processing', 'ready', 'completed', 'cancelled')")
                   ->orderBy('created_at', 'asc')
                   ->paginate(20);

    // Get counts for each status (juga filter outlet)
    $statusCounts = [
        'pending' => Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->where('status', 'pending')->count(),
        'processing' => Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->where('status', 'processing')->count(),
        'ready' => Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->where('status', 'ready')->count(),
        'completed' => Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->where('status', 'completed')->count(),
        'cancelled' => Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->where('status', 'cancelled')->count(),
    ];

    return view('penjual.orders.index', compact('orders', 'statusCounts', 'status', 'date'));
}

   public function show(Order $order)
{
    // Pastikan order milik outlet penjual yang login
    $outletIds = \App\Models\Outlet::where('user_id', auth()->id())->pluck('id');
    if (!$outletIds->contains($order->outlet_id)) {
        abort(403);
    }
    $order->load(['orderItems.menu', 'user']);
    return view('penjual.orders.show', compact('order'));
}

    public function updateStatus(Request $request, Order $order)
    {
         $outletIds = \App\Models\Outlet::where('user_id', auth()->id())->pluck('id');
    if (!$outletIds->contains($order->outlet_id)) {
        abort(403);
    }

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
    $outletIds = \App\Models\Outlet::where('user_id', auth()->id())->pluck('id');
    $orders = Order::whereIn('id', $request->order_ids)->whereIn('outlet_id', $outletIds);
    $orders->update(['status' => $request->status]);

    return response()->json([
        'success' => true,
        'message' => 'Status pesanan berhasil diperbarui secara batch'
    ]);
}
}
