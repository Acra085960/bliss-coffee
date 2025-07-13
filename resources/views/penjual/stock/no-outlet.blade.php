@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Outlet Belum Tersedia
                    </h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-store-slash fa-4x text-muted"></i>
                    </div>
                    
                    <h4 class="mb-3">Anda Belum Memiliki Outlet</h4>
                    
                    <p class="text-muted mb-4">
                        Untuk dapat mengelola stok, Anda harus memiliki outlet terlebih dahulu. 
                        Silakan hubungi owner atau manajer untuk mendapatkan assignment outlet.
                    </p>
                    
                    <div class="alert alert-info">
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2 text-start">
                            <li>Outlet harus di-assign oleh Owner</li>
                            <li>Setiap penjual bisa memiliki satu atau lebih outlet</li>
                            <li>Stok dikelola per outlet</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('penjual.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
