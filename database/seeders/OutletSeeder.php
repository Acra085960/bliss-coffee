<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\User;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil penjual dari user yang sudah ada
        $penjual = User::where('role', 'penjual')->first();

        Outlet::create([
            'name' => 'Gerobak Bliss Sultan Adam',
            'address' => 'Jl. Sultan Adam, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Utara',
            'lat' => -6.21462,
            'lng' => 106.84513,
            'phone' => '08123456789',
            'user_id' => $penjual ? $penjual->id : null,
        ]);

        Outlet::create([
            'name' => 'Gerobak Bliss Kayu Tangi',
            'address' => 'Jl. Kayu Tangi, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Utara',
            'lat' => -6.92144,
            'lng' => 107.60714,
            'phone' => '082233344455',
            'user_id' => $penjual ? $penjual->id : null,
        ]);
    }
}