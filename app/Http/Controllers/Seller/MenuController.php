<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->paginate(10);
        return view('penjual.menu.index', compact('menus'));
    }

    public function create()
    {
        $categories = ['Kopi', 'Non-Kopi', 'Makanan Ringan', 'Dessert'];
        return view('penjual.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $menuData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_available' => $request->has('is_available') ? true : false
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/menu'), $imageName);
            $menuData['image'] = 'menu/' . $imageName;
        }

        Menu::create($menuData);

        return redirect()->route('penjual.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $categories = ['Kopi', 'Non-Kopi', 'Makanan Ringan', 'Dessert'];
        return view('penjual.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $menuData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_available' => $request->has('is_available') ? true : false
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
                unlink(public_path('images/' . $menu->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/menu'), $imageName);
            $menuData['image'] = 'menu/' . $imageName;
        }

        $menu->update($menuData);

        return redirect()->route('penjual.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
            unlink(public_path('images/' . $menu->image));
        }

        $menu->delete();

        return redirect()->route('penjual.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    public function toggleAvailability(Menu $menu)
    {
        $menu->update([
            'is_available' => !$menu->is_available
        ]);

        $status = $menu->is_available ? 'tersedia' : 'tidak tersedia';
        return response()->json([
            'success' => true,
            'message' => "Menu berhasil diubah menjadi {$status}",
            'is_available' => $menu->is_available
        ]);
    }
}