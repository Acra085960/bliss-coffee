<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Midtrans\Transaction;
use Midtrans\Config;

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

        // Data tambahan untuk dashboard
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->sum('total_price');
        $menus = \App\Models\Menu::limit(6)->get();

        // Flag untuk menandai bahwa ini adalah tracking mode
        $isTracking = true;
        $trackedOrder = $order;

        return view('customer.dashboard', compact(
            'order',
            'recentOrders',
            'totalOrders',
            'totalSpent',
            'menus',
            'isTracking',
            'trackedOrder'
        ));
    }
    
    return redirect()->route('customer.dashboard')->with('error', 'Nomor pesanan tidak ditemukan!');
}

   public function cancelOrder(Request $request, Order $order)
{
    
    // Pastikan order milik user yang sedang login
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized access to order');
    }
    
    // Hanya boleh cancel jika status pending/processing
    if (!in_array($order->status, ['pending'])) {
        return response()->json(['error' => 'Pesanan tidak dapat dibatalkan'], 400);
    }

    // Inisialisasi Midtrans config
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

    // Jika pembayaran sudah settlement/capture, lakukan refund ke Midtrans
    if (in_array($order->payment_status, ['settlement', 'capture'])) {
         try {
            $refund = Transaction::refund($order->order_number, [
                'refund_key' => uniqid('refund_'),
                'amount' => $order->total_price,
                'reason' => 'Order dibatalkan oleh pembeli'
            ]);
            $order->update(['status' => 'cancelled', 'payment_status' => 'refunded']);
        } catch (\Exception $e) {
            // Tambahkan kode log di sini
            \Log::error('Refund gagal', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Refund gagal: ' . $e->getMessage()], 500);
        }
    } else {
        // Jika belum dibayar, cukup update status
        $order->update(['status' => 'cancelled']);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Pesanan berhasil dibatalkan' . (isset($refund) ? ' dan refund diproses.' : '')
    ]);
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
