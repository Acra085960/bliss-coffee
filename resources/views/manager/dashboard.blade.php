@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Dashboard Manager</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-dashboard-card title="Total Pesanan" : icon="shopping-cart" />
        <x-dashboard-card title="Menu Aktif" : icon="coffee" />
        <x-dashboard-card title="Stok Hampir Habis" : icon="alert-circle" />
    </div>
{{-- value="$totalOrders" value="$activeMenus" value="$lowStocks" orders="$latestOrders" --}}
    <div class="mt-6">
        <h2 class="text-lg font-medium mb-2">Pesanan Terbaru</h2>
        <x-order-table : />
    </div>
</div>
@endsection
