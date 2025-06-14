<!-- resources/views/customer/menu/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Menu</h1>
        
        <div class="row">
            @foreach ($menus as $menu)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $menu->name }} <!-- Nama menu -->
                        </div>
                        <div class="card-body">
                            <p>{{ $menu->description }}</p> <!-- Deskripsi menu -->
                            <p>Harga: Rp {{ number_format($menu->price, 0, ',', '.') }}</p> <!-- Harga menu -->
                            <a href="{{ route('customer.cart.add', $menu->id) }}" class="btn btn-primary">Tambah ke Keranjang</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
