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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Laporan Penjualan</h1>
        <div class="btn-group">
            <a href="{{ route('owner.reports.export', ['type' => 'csv']) }}" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i>Export CSV
            </a>
            <a href="{{ route('owner.reports.export', ['type' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <form method="GET" class="row g-3 mb-3">
        <div class="col-auto">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start }}">
        </div>
        <div class="col-auto">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end }}">
        </div>
        <div class="col-auto d-flex align-items-end">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
        </div>
        @if($start || $end)
        <div class="col-auto d-flex align-items-end">
            <a href="{{ route('owner.reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Reset
            </a>
        </div>
        @endif
    </form>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pendapatan</h5>
                            <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pesanan</h5>
                            <h3>{{ number_format($totalOrders) }}</h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Pesanan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor Pesanan</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <code>{{ $order->order_number ?? '#' . $order->id }}</code>
                            </td>
                            <td>{{ $order->customer_name ?? $order->user->name ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'ready' ? 'primary' : 'secondary'))) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($order->payment_method ?? 'cash') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>Tidak ada data pesanan</h5>
                                <p class="text-muted">Tidak ada pesanan pada periode yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection