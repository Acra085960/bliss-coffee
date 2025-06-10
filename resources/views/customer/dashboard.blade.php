<!-- resources/views/customer/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Customer Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Spent</h5>
                                    <p class="card-text">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($recentOrders->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Recent Orders</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentOrders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        // Tambahkan skrip JavaScript jika diperlukan
        console.log('Dashboard Pembeli loaded');
    </script>
@endsection