<!-- resources/views/customer/orders/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Riwayat Pesanan Anda</h1>

        @if ($orders->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor Pesanan</th>
                        <th>Status Pesanan</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->status }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Anda belum memiliki pesanan.</p>
        @endif
    </div>
@endsection
