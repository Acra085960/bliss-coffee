<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'pembeli']);
        Role::firstOrCreate(['name' => 'penjual']);
        Role::firstOrCreate(['name' => 'manajer']);
        Role::firstOrCreate(['name' => 'owner']);
    }
}
