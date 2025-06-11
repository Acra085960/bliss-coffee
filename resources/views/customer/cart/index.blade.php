<!-- resources/views/customer/cart/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Keranjang Belanja</h1>

        @if (count($cart) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('customer.checkout') }}" class="btn btn-success">Checkout</a>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
    </div>
@endsection
