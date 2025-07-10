{{-- filepath: /home/acra/bliss/resources/views/owner/dashboard.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .card.mb-3, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .table-responsive, .table {
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        .btn, .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
        h1, .card-title, .card-header, .font-bold {
            font-size: 1.1rem;
        }
        .form-select, .form-control {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .m-1 {
            margin: 0.3rem !important;
        }
        .d-flex.justify-content-between, .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
    }
    .card-body.table-responsive, .table-responsive {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="container">
    {{-- Executive Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">💰 Pendapatan Bulan Ini</h5>
                    <p class="card-text">Rp {{ number_format($monthlyRevenueNow, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">🧾 Pesanan Bulan Ini</h5>
                   <p class="card-text">{{ $monthlyOrdersNow }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">🧑‍🤝‍🧑 Pegawai Aktif</h5>
                    <p class="card-text">{{ $activeEmployees }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">📍 Outlet Aktif</h5>
                <p class="card-text">{{ $activeOutlets }}</p>
            </div>
        </div>
    </div>

    {{-- Grafik Keuangan dan Penjualan --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Pendapatan per Minggu (3 Bulan Terakhir)</div>
                <div class="card-body">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Order per Minggu (3 Bulan Terakhir)</div>
                <div class="card-body">
                    <canvas id="weeklyOrdersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Penjualan Bulanan --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Laporan Penjualan Bulanan</span>
                    <span>
                        <a href="{{ route('owner.reports.export', ['type' => 'csv']) }}" class="btn btn-sm btn-outline-primary">Export CSV</a>
                        <a href="{{ route('owner.reports.export', ['type' => 'pdf']) }}" class="btn btn-sm btn-outline-danger">Export PDF</a>
                    </span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Jumlah Order</th>
                                <th>Total Pendapatan</th>
                                <th>Menu Terlaris</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyReports as $report)
                            <tr>
                                <td>{{ $report['month'] }}</td>
                                <td>{{ $report['orders'] }}</td>
                                <td>Rp {{ number_format($report['revenue'], 0, ',', '.') }}</td>
                                <td>{{ $report['top_menu'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Feedback Konsumen --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                {{-- 
                <div class="card-header">Feedback Konsumen</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Nama Pembeli</th>
                                <th>Tanggal</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->customer->name ?? '-' }}</td>
                                <td>{{ $feedback->created_at->format('Y-m-d') }}</td>
                                <td>{{ $feedback->rating }}/5</td>
                                <td>{{ $feedback->comment }}</td>
                                <td>
                                    @if($feedback->is_read)
                                        <span class="badge bg-success">Dibaca</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Dibaca</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 
                --}}
            </div>
        </div>
    </div>

    {{-- Tabel Pegawai Singkat --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Manajemen Pegawai Singkat</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ ucfirst($employee->role) }}</td>
                                <td>
                                    @if($employee->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('owner.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('owner.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tautan Cepat --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tautan Cepat</div>
                <div class="card-body">
                    <a href="{{ route('owner.employees') }}" class="btn btn-outline-primary m-1">Kelola Pegawai</a>
                    <a href="{{ route('owner.menus') }}" class="btn btn-outline-info m-1">Kelola Harga Menu</a>
                    <a href="{{ route('owner.outlets') }}" class="btn btn-outline-warning m-1">Lihat Semua Outlet</a>
                    <a href="{{ route('owner.reports.index') }}" class="btn btn-outline-secondary m-1">Laporan Penjualan</a>
                    <a href="{{ route('owner.reports.export', ['type' => 'csv']) }}" class="btn btn-outline-success m-1">Ekspor CSV</a>
                    <a href="{{ route('owner.reports.export', ['type' => 'pdf']) }}" class="btn btn-outline-danger m-1">Ekspor PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const weekLabels = @json($weeklyLabels ?? []);
const weeklyRevenue = @json($weeklyRevenue ?? []);
const weeklyOrders = @json($weeklyOrders ?? []);

new Chart(document.getElementById('weeklyRevenueChart'), {
    type: 'bar',
    data: {
        labels: weekLabels,
        datasets: [{
            label: 'Pendapatan',
            data: weeklyRevenue,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('weeklyOrdersChart'), {
    type: 'line',
    data: {
        labels: weekLabels,
        datasets: [{
            label: 'Order',
            data: weeklyOrders,
            backgroundColor: 'rgba(255, 206, 86, 0.7)',
            borderColor: 'rgba(255, 206, 86, 1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush