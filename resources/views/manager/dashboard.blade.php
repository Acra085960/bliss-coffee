{{-- filepath: resources/views/manager/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Dashboard Manager</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-dashboard-card title="Total Pesanan" :value="$totalOrders" icon="shopping-cart" />
        <x-dashboard-card title="Menu Aktif" :value="$activeMenus" icon="coffee" />
        <x-dashboard-card title="Stok Hampir Habis" :value="$lowStocks" icon="alert-circle" />
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-medium mb-2">Pesanan Terbaru</h2>
        <x-order-table :orders="$latestOrders" />
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-medium mb-2">Menu Terlaris</h2>
        <ul>
            @foreach($topMenus as $menu)
                <li>{{ $menu->name }} ({{ $menu->orders_count }} pesanan)</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection