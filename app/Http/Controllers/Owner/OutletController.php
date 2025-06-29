<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        // Ambil semua outlet beserta user penanggung jawab dan orders
        $outlets = Outlet::with('user', 'orders')->get();
        // Ambil semua user dengan role penjual
        $penjuals = User::role('penjual')->get();
        return view('owner.outlets', compact('outlets', 'penjuals'));
    }

   public function assignPenjual(Request $request, Outlet $outlet)
    {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        // Jika user_id kosong, set null
        $outlet->user_id = $request->user_id ?: null;
        $outlet->save();

        return redirect()->route('owner.outlets')->with('success', 'Penjual berhasil di-assign ke outlet.');
    }
}