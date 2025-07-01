{{-- filepath: resources/views/manager/stocks.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    .outlet-card {
        border: 1px solid #e3e3e3;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .outlet-header {
        background: #f8fafc;
        border-bottom: 1px solid #e3e3e3;
        border-radius: 8px 8px 0 0;
        padding: 1rem 1.5rem;
        font-weight: bold;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .outlet-info {
        font-size: 0.95rem;
        color: #666;
    }
    .stock-table th, .stock-table td {
        vertical-align: middle !important;
    }
    .badge-status {
        font-size: 0.9em;
        padding: 0.4em 0.7em;
    }
    .outlet-label {
        background: #6366f1;
        color: #fff;
        border-radius: 4px;
        font-size: 0.95em;
        padding: 0.2em 0.7em;
        margin-right: 0.7em;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Monitoring Stok Bahan Baku per Outlet</h1>

    <form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label for="seller_id" class="form-label">Pilih Penjual/Outlet</label>
            <select name="seller_id" id="seller_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Penjual --</option>
                @foreach($allSellers as $seller)
                    <option value="{{ $seller->id }}" {{ (isset($selectedSellerId) && $selectedSellerId == $seller->id) ? 'selected' : '' }}>
                        {{ $seller->name }} ({{ $seller->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </div>
</form>

    @forelse($sellers as $seller)
        <div class="outlet-card">
            <div class="outlet-header">

                <div class="outlet-info">
                 
                    @if(isset($seller->outlets) && $seller->outlets->count())
                        <span class="ms-3"><i class="fas fa-store"></i> Total Outlet: {{ $seller->outlets->count() }}</span>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered stock-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Bahan Baku</th>
                                <th>Kategori</th>
                                <th>Sisa Stok</th>
                                <th>Satuan</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seller->stocks as $stock)
                                <tr>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->category }}</td>
                                    <td>
                                        <span class="fw-bold {{ $stock->current_stock <= $stock->minimum_stock ? 'text-danger' : 'text-success' }}">
                                            {{ $stock->current_stock }}
                                        </span>
                                    </td>
                                    <td>{{ $stock->unit }}</td>
                                    <td>{{ $stock->minimum_stock }}</td>
                                    <td>
                                        @if($stock->current_stock <= $stock->minimum_stock)
                                            <span class="badge bg-danger badge-status">Hampir Habis</span>
                                        @else
                                            <span class="badge bg-success badge-status">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada stok bahan baku untuk outlet ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada data penjual/outlet.</div>
    @endforelse
</div>
@endsection