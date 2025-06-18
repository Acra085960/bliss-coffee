{{-- filepath: /home/acra/bliss/resources/views/owner/reports.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Penjualan</h1>
    <form method="GET" class="row g-3 mb-3">
        <div class="col-auto">
            <input type="date" name="start_date" class="form-control" value="{{ $start }}">
        </div>
        <div class="col-auto">
            <input type="date" name="end_date" class="form-control" value="{{ $end }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
    </form>
    <div class="mb-3">
        <strong>Total Revenue:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}<br>
        <strong>Total Orders:</strong> {{ $totalOrders }}
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>{{ $order->customer_name ?? '-' }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection