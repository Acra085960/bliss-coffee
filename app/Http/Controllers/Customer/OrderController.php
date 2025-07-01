<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $period = $request->get('period', '30'); // days
        
        $query = Order::with(['orderItems.menu', 'user'])
                     ->where('user_id', auth()->id())
                     ->whereDate('created_at', '>=', now()->subDays($period));
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get order statistics
        $stats = $this->getOrderStatistics();
        
        return view('customer.orders', compact('orders', 'stats', 'status', 'period'));
    }

    public function show(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }
        
        $order->load(['orderItems.menu', 'user']);
        return view('customer.order-detail', compact('order'));
    }

    public function reorder(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        $cart = session()->get('cart', []);
        
        foreach ($order->orderItems as $item) {
            if ($item->menu && $item->menu->is_available) {
                $cartKey = $item->menu_id . '_' . md5($item->preferences ?? '');
                
                if (isset($cart[$cartKey])) {
                    $cart[$cartKey]['quantity'] += $item->quantity;
                } else {
                    $cart[$cartKey] = [
                        'id' => $item->menu->id,
                        'name' => $item->menu->name,
                        'price' => $item->menu->price, // Use current price
                        'quantity' => $item->quantity,
                        'image' => $item->menu->image,
                        'category' => $item->menu->category ?? 'Menu',
                        'preferences' => $item->preferences
                    ];
                }
            }
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('customer.cart')->with('success', 'Items from order #' . $order->order_number . ' have been added to your cart!');
    }

    public function trackOrder(Request $request, $orderNumber = null)
    {
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)
                         ->where('user_id', auth()->id())
                         ->with(['orderItems.menu'])
                         ->first();
            
            if (!$order) {
                return redirect()->route('customer.orders')->with('error', 'Pesanan tidak ditemukan!');
            }
            
            return view('customer.track-order', compact('order'));
        }
        
        // Show form to enter order number
        return redirect()->route('customer.dashboard')->with('error', 'Nomor pesanan tidak ditemukan!');
    }

    public function getOrderStatus(Request $request)
    {
        $orderNumber = $request->get('order_number');
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', auth()->id())
                     ->with(['orderItems.menu'])
                     ->first();
        
        if (!$order) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }
        
        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status ?? 'pending',
            'estimated_time' => $this->getEstimatedTime($order),
            'progress_percentage' => $this->getProgressPercentage($order->status),
            'status_message' => $this->getStatusMessage($order->status),
            'updated_at' => $order->updated_at->format('H:i'),
            'can_cancel' => in_array($order->status, ['pending', 'processing'])
        ]);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }
        
        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json(['error' => 'Pesanan tidak dapat dibatalkan'], 400);
        }
        
        $order->update(['status' => 'cancelled']);
        
        return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibatalkan']);
    }

    private function getEstimatedTime($order)
    {
        switch ($order->status) {
            case 'pending':
                return '15-20 menit';
            case 'processing':
                return '10-15 menit';
            case 'ready':
                return 'Siap diambil';
            case 'completed':
                return 'Selesai';
            case 'cancelled':
                return 'Dibatalkan';
            default:
                return 'Unknown';
        }
    }

    private function getProgressPercentage($status)
    {
        switch ($status) {
            case 'pending':
                return 25;
            case 'processing':
                return 50;
            case 'ready':
                return 75;
            case 'completed':
                return 100;
            case 'cancelled':
                return 0;
            default:
                return 0;
        }
    }

    private function getStatusMessage($status)
    {
        switch ($status) {
            case 'pending':
                return 'Pesanan Anda sedang menunggu konfirmasi';
            case 'processing':
                return 'Pesanan Anda sedang disiapkan';
            case 'ready':
                return 'Pesanan Anda siap diambil';
            case 'completed':
                return 'Pesanan Anda telah selesai';
            case 'cancelled':
                return 'Pesanan Anda telah dibatalkan';
            default:
                return 'Status tidak diketahui';
        }
    }

    private function getOrderStatistics()
    {
        $userId = auth()->id();
        
        // Check if payment_status column exists
        $hasPaymentStatus = Schema::hasColumn('orders', 'payment_status');
        
        $totalSpentQuery = Order::where('user_id', $userId);
        
        if ($hasPaymentStatus) {
            $totalSpentQuery->where('payment_status', 'paid');
        }
        
        return [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'completed_orders' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
            'pending_orders' => Order::where('user_id', $userId)->whereIn('status', ['pending', 'processing', 'ready'])->count(),
            'total_spent' => $totalSpentQuery->sum('total_price'),
            'favorite_items' => $this->getFavoriteItems(),
        ];
    }

    private function getFavoriteItems()
    {
        return \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('orders.user_id', auth()->id())
            ->select('menus.name', \DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(3)
            ->get();
    }
}
