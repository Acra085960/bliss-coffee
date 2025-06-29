<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1|max:10',
            'preferences' => 'nullable|string|max:255'
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        
        if (!$menu->is_available) {
            return redirect()->back()->with('error', 'Menu tidak tersedia!');
        }

        $cart = Session::get('cart', []);
        
        // Create unique key based on menu and preferences
        $cartKey = $menu->id . '_' . md5($request->preferences ?? '');
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
            
            // Limit max quantity per item
            if ($cart[$cartKey]['quantity'] > 10) {
                $cart[$cartKey]['quantity'] = 10;
            }
        } else {
            $cart[$cartKey] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'image' => $menu->image,
                'category' => $menu->category ?? 'Menu',
                'preferences' => $request->preferences
            ];
        }

        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', $menu->name . ' berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = $request->quantity;
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diupdate'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->key])) {
            $itemName = $cart[$request->key]['name'];
            unset($cart[$request->key]);
            Session::put('cart', $cart);
            
            return redirect()->back()->with('success', $itemName . ' berhasil dihapus dari keranjang!');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan!');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    public function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return response()->json([
            'total' => $total,
            'formatted' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

 public function updatePreference(Request $request, $key)
{
    $cart = session()->get('cart', []);
    if (isset($cart[$key])) {
        $cart[$key]['preferences'] = $request->preferences;
        session()->put('cart', $cart);
    }
    return back()->with('success', 'Preferensi berhasil diubah.');
}
}
