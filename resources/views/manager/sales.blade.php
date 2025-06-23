{{-- filepath: /home/acra/bliss/resources/views/manager/sales.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .card, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .table-responsive, .table {
            font-size: 0.95rem;
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
        .table th, .table td {
            padding: 0.5rem;
        }
        #salesChart {
            min-height: 180px;
            max-width: 100%;
        }
    }
    /* Agar tabel bisa discroll di layar kecil */
    .card-body.p-0 {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Analisis Penjualan</h1>

    {{-- Grafik Penjualan (gunakan Chart.js atau library lain jika ingin interaktif) --}}
    <div class="card mb-4">
        <div class="card-header font-bold">Grafik Penjualan Harian</div>
        <div class="card-body">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    {{-- Ringkasan Penjualan --}}
    <div class="card mb-4">
        <div class="card-header font-bold">Ringkasan Penjualan</div>
        <div class="card-body">
            <ul>
                <li>Total Penjualan Hari Ini: <b>Rp{{ number_format($todaySales ?? 0, 0, ',', '.') }}</b></li>
                <li>Total Penjualan Minggu Ini: <b>Rp{{ number_format($weekSales ?? 0, 0, ',', '.') }}</b></li>
                <li>Jumlah Pesanan Hari Ini: <b>{{ $todayOrders ?? 0 }}</b></li>
                <li>Jumlah Pesanan Minggu Ini: <b>{{ $weekOrders ?? 0 }}</b></li>
            </ul>
        </div>
    </div>

    {{-- Tabel Penjualan --}}
    <div class="card">
        <div class="card-header font-bold">Daftar Penjualan Terbaru</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
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
                    @forelse($recentSales ?? [] as $sale)
                        <tr>
                            <td>{{ $sale->created_at->format('d M Y') }}</td>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->user->name ?? '-' }}</td>
                            <td>Rp{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($sale->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($chartData ?? []) !!},
                backgroundColor: 'rgba(52, 211, 153, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection