<!-- resources/views/manajer/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Manajer</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Total Penjualan</div>
                    <div class="card-body">
                        <p>Total Penjualan Minggu Ini: Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Pesanan Selesai</div>
                    <div class="card-body">
                        <p>Jumlah Pesanan yang Selesai: {{ $completedOrders }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
