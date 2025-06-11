<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong!');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'total_price' => $total,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Clear cart
            Session::forget('cart');
            
            DB::commit();
            
            // Redirect to main menu with success message
            return redirect()->route('customer.test')->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan berbelanja atau lihat riwayat pesanan Anda.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }
    }
}
