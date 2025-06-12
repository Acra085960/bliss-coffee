<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
   public function index()
    {
        $menus = \App\Models\Menu::all(); 
        return view('admin.menu.index' , compact('menus'));
    }
    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        // Create the new menu item
        Menu::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        // Redirect back with a success message
        return redirect()->route('penjual.menu.index')->with('success', 'Menu item created successfully!');
    }
    
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }


    public function update(Request $request, Menu $menu)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        // Update the menu item
        $menu->update($validated);

        // Redirect back with a success message
        return redirect()->route('penjual.menu.index')->with('success', 'Menu item updated successfully!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('penjual.menu.index')->with('success', 'Menu item deleted successfully!');
    }
}