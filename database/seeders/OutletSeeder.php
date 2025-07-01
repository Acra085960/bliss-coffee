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

        Outlet::create([
            'name' => 'Gerobak Bliss Sungai Andai',
            'address' => 'Jl. Sungai Andai, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Utara',
            'lat' => -3.27890,
            'lng' => 114.60310,
            'phone' => '08111111111',
            'user_id' => $penjual ? $penjual->id : null,
        ]);

        Outlet::create([
            'name' => 'Gerobak Bliss Sungai Miai',
            'address' => 'Jl. Sungai Miai, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Utara',
            'lat' => -3.29500,
            'lng' => 114.59400,
            'phone' => '08222222222',
            'user_id' => $penjual ? $penjual->id : null,
        ]);

        Outlet::create([
            'name' => 'Gerobak Bliss Teluk Dalam',
            'address' => 'Jl. Teluk Dalam, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Tengah',
            'lat' => -3.32000,
            'lng' => 114.59000,
            'phone' => '08333333333',
            'user_id' => $penjual ? $penjual->id : null,
        ]);

        Outlet::create([
            'name' => 'Gerobak Bliss Gatot Subroto',
            'address' => 'Jl. Gatot Subroto, Banjarmasin',
            'city' => 'Banjarmasin',
            'province' => 'Banjarmasin Tengah',
            'lat' => -3.33000,
            'lng' => 114.60000,
            'phone' => '08444444444',
            'user_id' => $penjual ? $penjual->id : null,
        ]);
    }
}