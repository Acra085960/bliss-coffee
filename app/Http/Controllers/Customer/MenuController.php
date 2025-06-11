<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = \App\Models\Menu::all(); 
        return view('customer.menu' , compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }
}
