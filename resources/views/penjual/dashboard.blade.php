<!-- resources/views/penjual/dashboard.blade.php -->

@extends('layouts.app')
@push('styles')
<style>
    /* Make cards stack on mobile */
    @media (max-width: 767.98px) {
        .row.mb-4 > [class^="col-"], .row.mb-4 > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .table-responsive {
            font-size: 0.95rem;
        }
        .progress {
            height: 14px !important;
        }
        .btn, .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
        h1.h3, .card-title, .card-header h5 {
            font-size: 1.1rem;
        }
    }
    /* Make chart containers responsive */
    .card-body canvas {
        width: 100% !important;
        height: 220px !important;
        max-width: 100%;
    }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">Dashboard Barista - {{ \Carbon\Carbon::today()->format('d M Y') }}</h1>
        </div>
    </div>

    <!-- Daily Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Pesanan Hari Ini</h5>
                            <h2 class="mb-0">{{ $todayOrders }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Pesanan Pending</h5>
                            <h2 class="mb-0">{{ $pendingOrders }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Sedang Diproses</h5>
                            <h2 class="mb-0">{{ $processingOrders }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-spinner fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Selesai</h5>
                            <h2 class="mb-0">{{ $completedOrders }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Pesanan Hari Ini</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Pesanan per Jam</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourlyOrderChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue and Popular Items -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pendapatan Hari Ini</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-success">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    <p class="text-muted">Rata-rata per pesanan: Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Menu Terpopuler Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($popularMenus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($popularMenus as $menu)
                                    <tr>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $menu->total_quantity }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($menu->total_quantity / $popularMenus->first()->total_quantity) * 100 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Belum ada data menu hari ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan Terbaru</h5>
                    <a href="{{ route('penjual.orders.index') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Pelanggan</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @foreach($order->orderItems as $item)
                                                <small>{{ $item->menu->name ?? 'N/A' }} ({{ $item->quantity }}x)</small><br>
                                            @endforeach
                                        </td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('H:i') }}</td>
                                        <td>
                                            @if($order->status === 'pending')
                                                <button class="btn btn-sm btn-info" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                    Proses
                                                </button>
                                            @elseif($order->status === 'processing')
                                                <button class="btn btn-sm btn-success" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                                    Selesai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Belum ada pesanan hari ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Order Status Pie Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
        datasets: [{
            data: [{{ $pendingOrders }}, {{ $processingOrders }}, {{ $completedOrders }}, {{ $cancelledOrders }}],
            backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Hourly Orders Bar Chart
const hourlyCtx = document.getElementById('hourlyOrderChart').getContext('2d');
const hourlyData = @json($hourlyOrders);

const hours = Array.from({length: 24}, (_, i) => i);
const orderCounts = hours.map(hour => {
    const found = hourlyData.find(item => item.hour == hour);
    return found ? found.count : 0;
});

const hourlyOrderChart = new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: hours.map(h => h + ':00'),
        datasets: [{
            label: 'Pesanan per Jam',
            data: orderCounts,
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Update Order Status Function
function updateOrderStatus(orderId, status) {
    if(confirm('Apakah Anda yakin ingin mengubah status pesanan?')) {
        fetch(`/penjual/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({status: status})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat mengupdate status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate status');
        });
    }
}

// Auto refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
@endsection