<!-- resources/views/penjual/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Penjual</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Pesanan Hari Ini</div>
                    <div class="card-body">
                        <p>Total Pesanan: {{ $orderCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Menu yang Tersedia</div>
                    <div class="card-body">
                        <p>Total Menu: {{ $menuCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Stok Bahan</div>
                    <div class="card-body">
                        <!-- Anda bisa menampilkan stok bahan di sini jika diperlukan -->
                        <p>Stok bahan perlu diperiksa secara berkala.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
