@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="font-semibold text-xl mb-4">Total Sales Today</h2>
                <p class="text-lg">Rp 2,500,000</p> <!-- Placeholder for total sales -->
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="font-semibold text-xl mb-4">Menu Items Available</h2>
                <p class="text-lg">12 items</p> <!-- Placeholder for menu count -->
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="font-semibold text-xl mb-4">Orders Pending</h2>
                <p class="text-lg">5 orders</p> <!-- Placeholder for orders -->
            </div>
        </div>
    </div>
@endsection
