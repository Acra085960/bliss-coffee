@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .row.mb-4 > [class^="col-"], .row.mb-4 > [class*=" col-"],
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .order-card {
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
        h1, .card-title, .card-header h5 {
            font-size: 1.1rem;
        }
        .form-select, .form-control {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .order-card {
            min-height: unset;
        }
        .nav-tabs .nav-link {
            font-size: 0.98rem;
            padding: 0.5rem 0.7rem;
        }
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        .btn-group .form-control {
            width: 100% !important;
            margin-bottom: 0.5rem;
        }
        .btn-group .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manajemen Pesanan</h1>
            <p class="text-muted">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <input type="date" class="form-control" id="dateFilter" value="{{ $date }}" style="width: auto; display: inline-block;">
                <button class="btn btn-primary ms-2" onclick="filterByDate()">Filter</button>
            </div>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $statusCounts['pending'] }}</h3>
                    <small>Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $statusCounts['processing'] }}</h3>
                    <small>Diproses</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $statusCounts['ready'] }}</h3>
                    <small>Siap Diambil</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $statusCounts['completed'] }}</h3>
                    <small>Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ $statusCounts['cancelled'] }}</h3>
                    <small>Dibatalkan</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100 h-100" onclick="toggleBatchMode()">
                <i class="fas fa-check-square"></i><br>Batch Mode
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-3" id="statusTabs">
        <li class="nav-item">
            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="?status=all&date={{ $date }}">
                Semua ({{ array_sum($statusCounts) }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" href="?status=pending&date={{ $date }}">
                Menunggu ({{ $statusCounts['pending'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'processing' ? 'active' : '' }}" href="?status=processing&date={{ $date }}">
                Diproses ({{ $statusCounts['processing'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'ready' ? 'active' : '' }}" href="?status=ready&date={{ $date }}">
                Siap ({{ $statusCounts['ready'] }})
            </a>
        </li>
    </ul>

    <!-- Batch Actions (Hidden by default) -->
    <div id="batchActions" class="alert alert-info" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Mode Batch Aktif</strong> - Pilih pesanan untuk update massal
            </div>
            <div class="btn-group">
                <button class="btn btn-info btn-sm" onclick="batchUpdate('processing')">Proses Terpilih</button>
                <button class="btn btn-primary btn-sm" onclick="batchUpdate('ready')">Siap Terpilih</button>
                <button class="btn btn-success btn-sm" onclick="batchUpdate('completed')">Selesai Terpilih</button>
                <button class="btn btn-secondary btn-sm" onclick="toggleBatchMode()">Keluar</button>
            </div>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card order-card h-100 border-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'ready' ? 'primary' : ($order->status === 'completed' ? 'success' : 'danger'))) }}">
                <!-- Batch Checkbox (Hidden by default) -->
                <div class="batch-checkbox" style="display: none;">
                    <input type="checkbox" class="form-check-input position-absolute" style="top: 10px; left: 10px; z-index: 10;" value="{{ $order->id }}">
                </div>
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">#{{ $order->id }}</h6>
                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'ready' ? 'primary' : ($order->status === 'completed' ? 'success' : 'danger'))) }}">
                        {{ ucfirst($order->status === 'ready' ? 'Siap' : ($order->status === 'processing' ? 'Diproses' : $order->status)) }}
                    </span>
                </div>
                
                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="mb-3">
                        <strong>{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</strong>
                        @if($order->customer_phone)
                            <br><small class="text-muted">{{ $order->customer_phone }}</small>
                        @endif
                    </div>
                    
                    <!-- Order Items -->
                    <div class="mb-3">
                        <h6>Items:</h6>
                        @foreach($order->orderItems as $item)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $item->menu->name ?? 'Menu N/A' }}</strong>
                                <span class="badge bg-secondary">{{ $item->quantity }}x</span>
                            </div>
                            @if($item->preferences)
                            <div class="mt-1">
                                <small class="text-info">
                                    <i class="fas fa-star"></i> {{ $item->preferences }}
                                </small>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    @if($order->notes)
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-sticky-note"></i> {{ $order->notes }}
                        </small>
                    </div>
                    @endif
                    
                    <div class="text-center">
                        <strong class="h5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex gap-1 flex-wrap">
                        @if($order->status === 'pending')
                            <button class="btn btn-info btn-sm flex-fill" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                <i class="fas fa-play"></i> Mulai
                            </button>
                        @elseif($order->status === 'processing')
                            <button class="btn btn-primary btn-sm flex-fill" onclick="updateOrderStatus({{ $order->id }}, 'ready')">
                                <i class="fas fa-bell"></i> Siap
                            </button>
                        @elseif($order->status === 'ready')
                            <button class="btn btn-success btn-sm flex-fill" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                <i class="fas fa-check"></i> Selesai
                            </button>
                        @endif
                        
                        <a href="{{ route('penjual.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <button class="btn btn-outline-danger btn-sm" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4>Tidak ada pesanan</h4>
                <p class="text-muted">Belum ada pesanan untuk tanggal ini.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

<script>
let batchMode = false;

function filterByDate() {
    const date = document.getElementById('dateFilter').value;
    const currentStatus = '{{ $status }}';
    window.location.href = `?status=${currentStatus}&date=${date}`;
}

function toggleBatchMode() {
    batchMode = !batchMode;
    const checkboxes = document.querySelectorAll('.batch-checkbox');
    const batchActions = document.getElementById('batchActions');
    
    checkboxes.forEach(checkbox => {
        checkbox.style.display = batchMode ? 'block' : 'none';
    });
    
    batchActions.style.display = batchMode ? 'block' : 'none';
}

function batchUpdate(status) {
    const selectedOrders = Array.from(document.querySelectorAll('.batch-checkbox input:checked')).map(cb => cb.value);
    
    if (selectedOrders.length === 0) {
        alert('Pilih minimal satu pesanan');
        return;
    }
    
    if (confirm(`Update ${selectedOrders.length} pesanan ke status ${status}?`)) {
        fetch('/penjual/orders/batch-update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_ids: selectedOrders,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan');
            }
        });
    }
}

function updateOrderStatus(orderId, status) {
    const statusText = {
        'processing': 'memproses',
        'ready': 'menyiapkan',
        'completed': 'menyelesaikan',
        'cancelled': 'membatalkan'
    };
    
    if(confirm(`Apakah Anda yakin ingin ${statusText[status]} pesanan ini?`)) {
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
                // Show success message
                showToast(data.message, 'success');
                // Reload after short delay
                setTimeout(() => location.reload(), 1000);
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

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

// Auto refresh every 30 seconds for active orders
if ('{{ $status }}' !== 'completed') {
    setInterval(() => {
        if (!batchMode) location.reload();
    }, 30000);
}
</script>

<style>
.order-card {
    transition: transform 0.2s;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.batch-checkbox {
    position: relative;
}
</style>
@endsection
