{{-- filepath: /home/acra/bliss/resources/views/owner/reports.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1 {
            font-size: 1.2rem;
        }
        .btn, .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        .table-responsive, .table {
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        .row.g-3 > [class^="col-"], .row.g-3 > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 0.7rem;
        }
    }
    /* Agar tabel bisa discroll di layar kecil */
    .table-responsive, .container .table {
        overflow-x: auto;
        display: block;
    }
</style>
@endpush

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