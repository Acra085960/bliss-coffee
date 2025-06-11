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
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $cart = Session::get('cart', []);

        $menuId = $menu->id;
        
        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += $request->quantity;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'image' => $menu->image ?? null
            ];
        }

        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Item berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'menu_id' => 'required'
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->menu_id])) {
            unset($cart[$request->menu_id]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}
