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
        $outlets = Outlet::with('user', 'orders')->get();
        $penjuals = User::where('role', 'penjual')->get(); // pastikan ini ada

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

    public function toggleActive($id)
{
    $outlet = \App\Models\Outlet::findOrFail($id);
    $outlet->is_active = !$outlet->is_active;
    $outlet->save();

    return back()->with('success', 'Status outlet berhasil diubah.');
}
}