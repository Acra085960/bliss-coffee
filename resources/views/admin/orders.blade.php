@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Daftar Pesanan</h1>

    <x-order-table :orders="$orders" />
</div>
@endsection
