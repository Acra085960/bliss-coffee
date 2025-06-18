<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;

class OutletController extends Controller
{
    public function index()
    {
        // Jika satu penjual = satu gerobak/outlet
        $outlets = User::where('role', 'penjual')->get();
        return view('owner.outlets', compact('outlets'));
    }
}