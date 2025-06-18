<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('owner.menus', compact('menus'));
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('owner.edit.menu', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $menu->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);
        return redirect()->route('owner.menus')->with('success', 'Menu updated!');
    }
}