<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong!');
        }
        
        $subtotal = 0;
        $totalItems = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }
        
        $tax = 0; // No tax for now
        $serviceFee = 2500; // Service fee
        $total = $subtotal + $tax + $serviceFee;
        
        return view('customer.checkout', compact('cart', 'subtotal', 'tax', 'serviceFee', 'total', 'totalItems'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,midtrans',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $serviceFee = 2500;
        $total = $subtotal + $serviceFee;

        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'total_price' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_number' => 'BC-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'preferences' => $item['preferences'] ?? null
                ]);
            }

            DB::commit();

            // Handle payment method
            if ($request->payment_method === 'midtrans') {
                return $this->processMidtransPayment($order);
            } else {
                // Cash payment - mark as pending payment
                Session::forget('cart');
                return redirect()->route('customer.order-success', $order->id);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    private function processMidtransPayment(Order $order)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => auth()->user()->email,
                'phone' => $order->customer_phone,
            ],
            'item_details' => [],
        ];

        // Add order items
        foreach ($order->orderItems as $item) {
            $params['item_details'][] = [
                'id' => $item->menu_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->menu->name ?? 'Menu Item',
            ];
        }

        // Add service fee
        $params['item_details'][] = [
            'id' => 'SERVICE_FEE',
            'price' => 2500,
            'quantity' => 1,
            'name' => 'Service Fee',
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Store snap token in order
            $order->update(['snap_token' => $snapToken]);
            
            return view('customer.payment', compact('order', 'snapToken'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function paymentCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        if ($hashed == $request->signature_key) {
            $order = Order::where('order_number', $request->order_id)->first();
            
            if ($order) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now()
                    ]);
                    
                    // Clear cart after successful payment
                    Session::forget('cart');
                    
                    return response()->json(['status' => 'success']);
                } elseif ($request->transaction_status == 'pending') {
                    $order->update(['payment_status' => 'pending']);
                    return response()->json(['status' => 'pending']);
                } else {
                    $order->update(['payment_status' => 'failed']);
                    return response()->json(['status' => 'failed']);
                }
            }
        }
        
        return response()->json(['status' => 'invalid']);
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with(['orderItems.menu', 'user'])->findOrFail($orderId);
        
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('customer.order-success', compact('order'));
    }
}
