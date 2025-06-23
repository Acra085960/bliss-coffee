{{-- filepath: /home/acra/bliss/resources/views/manager/dashboard.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .card.mb-3 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .table-responsive {
            font-size: 0.95rem;
        }
        .btn, .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
        h1, .card-title, .card-header, .h4 {
            font-size: 1.1rem;
        }
        .form-select, .form-control {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .mb-3, .mt-4 {
            margin-bottom: 1rem !important;
            margin-top: 1rem !important;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        .chart-container {
            min-height: 220px;
        }
    }
    /* Chart container for better mobile scroll */
    .chart-container {
        width: 100%;
        overflow-x: auto;
    }
</style>
@endpush\

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Manager Dashboard') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Penjualan Hari Ini</h5>
                                    <p class="card-text">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pesanan Hari Ini</h5>
                                    <p class="card-text">{{ $totalOrdersToday }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Menu Stok Habis</h5>
                                    <p class="card-text">{{ $outOfStockMenus }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Bahan Hampir Habis</h5>
                                    <p class="card-text">{{ $lowStockIngredients }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Penjualan 7 Hari Terakhir</div>
                                <div class="card-body">
                                    <canvas id="sales7Chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Menu Terlaris Minggu Ini</div>
                                <div class="card-body">
                                    <canvas id="topMenusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Tabel Stok Menu</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Menu</th>
                                                    <th>Stok Tersisa</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($menus as $menu)
                                                <tr>
                                                    <td>{{ $menu->name }}</td>
                                                    <td>{{ $menu->stock->current_stock ?? 0 }}</td>
                                                    <td>
                                                        @if(($menu->stock->current_stock ?? 0) > 0)
                                                            <span class="badge bg-success">Tersedia</span>
                                                        @else
                                                            <span class="badge bg-danger">Habis</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Tabel Bahan Baku</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Bahan</th>
                                                    <th>Jumlah Tersisa</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ingredients as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->current_stock }}</td>
                                                    <td>
                                                        @if($item->current_stock == 0)
                                                            <span class="badge bg-danger">Habis</span>
                                                        @elseif($item->current_stock < $item->minimum_stock)
                                                            <span class="badge bg-warning text-dark">Hampir habis</span>
                                                        @else
                                                            <span class="badge bg-success">Aman</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Kinerja Penjual Minggu Ini</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Penjual</th>
                                                    <th>Jumlah Pesanan Selesai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sellerPerformance as $seller)
                                                <tr>
                                                    <td>{{ $seller->name }}</td>
                                                    <td>{{ $seller->orders_count }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">Total Pendapatan Minggu Ini</div>
                                <div class="card-body">
                                    <p class="h4 text-success">Rp {{ number_format($totalRevenueWeek, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">Rata-rata Order per Hari (Minggu Ini)</div>
                                <div class="card-body">
                                    <p class="h4 text-primary">{{ number_format($avgOrderPerDay, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const sales7Days = @json($sales7Days);
const dates = @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
const topMenus = @json($topMenus->pluck('name'));
const topMenusCount = @json($topMenus->pluck('orders_count'));

new Chart(document.getElementById('sales7Chart'), {
    type: 'bar',
    data: {
        labels: dates,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: sales7Days,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('topMenusChart'), {
    type: 'pie',
    data: {
        labels: topMenus,
        datasets: [{
            label: 'Menu Terlaris',
            data: topMenusCount,
            backgroundColor: [
                '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'
            ]
        }]
    },
    options: { responsive: true }
});
</script>
@endpush