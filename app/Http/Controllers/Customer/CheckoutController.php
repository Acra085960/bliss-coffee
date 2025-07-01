<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{

public function index()
{
    $cart = session('cart', []);
    $subtotal = 0;
    $totalItems = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }
    $tax = 0;
    $serviceFee = 2500;
    $total = $subtotal + $tax + $serviceFee;

    // Ambil semua outlet untuk dropdown
    $outlets = \App\Models\Outlet::whereNotNull('user_id')->get();

    return view('customer.checkout', compact('cart', 'subtotal', 'tax', 'serviceFee', 'total', 'totalItems', 'outlets'));
}

public function process(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'outlet_id' => 'required|exists:outlets,id',
        'payment_method' => 'required|in:cash,midtrans',
        'notes' => 'nullable|string|max:500'
    ]);

    $cart = session('cart', []);
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
        // Buat order_number unik
        do {
            $orderNumber = 'BC-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
        } while (\App\Models\Order::where('order_number', $orderNumber)->exists());

        // Simpan order, termasuk outlet_id
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_price' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_number' => $orderNumber,
            'notes' => $request->notes,
            'outlet_id' => $request->outlet_id, // simpan outlet yang dipilih
        ]);

        // Simpan item pesanan (OrderItem)
        foreach ($cart as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'preferences' => $item['preferences'] ?? null
            ]);
        }

        DB::commit();

        // Proses pembayaran jika online
        if ($request->payment_method === 'midtrans') {
            return $this->processMidtransPayment($order);
        } else {
            session()->forget('cart');
            return redirect()->route('customer.order-success', $order->id);
        }
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
    }
}

    protected function processMidtransPayment($order)
    {
        // Contoh implementasi Midtrans Snap (sesuaikan dengan kebutuhanmu)
        // Pastikan sudah mengatur konfigurasi Midtrans di config/services.php
 \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$clientKey = config('midtrans.client_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        // 1. Set parameter Snap
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone,
            ],
        ];

        // 2. Dapatkan Snap Token
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return redirect()->route('customer.checkout')->with('error', 'Gagal memproses pembayaran online: ' . $e->getMessage());
        }

        // 3. Redirect ke halaman pembayaran (atau tampilkan snap token di view)
        return view('customer.payment', compact('order', 'snapToken'));
    }

    public function orderSuccess($orderId)
{
    $order = \App\Models\Order::with(['orderItems.menu', 'outlet'])->findOrFail($orderId);
    return view('customer.order-success', compact('order'));
}
}