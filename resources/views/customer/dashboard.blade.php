<!-- resources/views/customer/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Pembeli</h1>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Status Pesanan Terakhir</div>
                    <div class="card-body">
                        <p>{{ $orderStatus }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Rekomendasi Menu</div>
                    <div class="card-body">
                        <p>Anda dapat melihat menu favorit kami di halaman menu.</p>
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