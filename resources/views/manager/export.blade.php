{{-- filepath: /home/acra/bliss/resources/views/manager/export.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Ekspor Data Penjualan</h1>

    <div class="card mb-4">
        <div class="card-header font-bold">Filter Data Penjualan</div>
        <div class="card-body">
            <form method="GET" action="{{ route('manajer.sales.export') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="from" class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                    <a href="{{ route('manajer.sales.export.csv', request()->only(['from','to'])) }}" class="btn btn-success me-2">
                        <i class="fas fa-file-csv"></i> Ekspor CSV
                    </a>
                    <a href="{{ route('manajer.sales.export.pdf', request()->only(['from','to'])) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Ekspor PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header font-bold">Data Penjualan</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->created_at->format('d M Y') }}</td>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->user->name ?? '-' }}</td>
                            <td>Rp{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($sale->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection