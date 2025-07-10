@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container-fluid, .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1, h4, h5 {
            font-size: 1.1rem;
        }
        .row.mb-4 > [class^="col-"], .row.mb-4 > [class*=" col-"],
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .order-card, .card.mb-3, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .btn, .btn-sm, .btn-lg {
            font-size: 0.98rem;
            padding: 0.6rem 1rem;
        }
        .form-select, .form-control {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .order-items {
            max-height: none;
        }
        .timeline-sm {
            display: none;
        }
        .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .text-end, .text-right {
            text-align: left !important;
            margin-top: 1rem;
        }
        .pagination {
            flex-wrap: wrap;
        }
    }
    /* Agar tabel/card bisa discroll di layar kecil */
    .table-responsive, .container .table, .card-body.p-0 {
        overflow-x: auto;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Riwayat Pesanan</h1>
            <p class="text-muted">Lihat dan kelola semua pesanan Anda</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('customer.menu') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Pesan Lagi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Order Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $stats['total_orders'] }}</h3>
                    <small>Total Pesanan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $stats['completed_orders'] }}</h3>
                    <small>Pesanan Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $stats['pending_orders'] }}</h3>
                    <small>Pesanan Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                    <small>Total Belanja</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Items -->
    @if($stats['favorite_items']->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Menu Favorit Anda</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($stats['favorite_items'] as $item)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-heart text-danger me-2"></i>
                                <div>
                                    <strong>{{ $item->name }}</strong><br>
                                    <small class="text-muted">Dipesan {{ $item->total_quantity }} kali</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-select" id="statusFilter" onchange="applyFilters()">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ $status == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="periodFilter" onchange="applyFilters()">
                <option value="7" {{ $period == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="30" {{ $period == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="90" {{ $period == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="365" {{ $period == '365' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-md-12 mb-4">
                <div class="card order-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <strong>Pesanan #{{ $order->order_number }}</strong>
                                    @if(isset($order->payment_method))
                                        <span class="badge bg-secondary ms-2">{{ ucfirst($order->payment_method) }}</span>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'ready' ? 'primary' : 'secondary'))) }} fs-6">
                                    {{ ucfirst($order->status === 'ready' ? 'Siap Diambil' : $order->status) }}
                                </span>
                                @if(isset($order->payment_status))
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }} ms-1">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Order Items -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Item Pesanan</h6>
                                    <span class="badge bg-info">{{ $order->orderItems->sum('quantity') }} item</span>
                                </div>
                                <div class="order-items mb-3">
                                    @foreach($order->orderItems as $item)
                                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-light">
                                        <div class="me-3">
                                            @php
                                                $imageUrl = null;
                                                $altText = 'Menu Image';
                                                
                                                // Try multiple image sources with priority
                                                if ($item->menu && $item->menu->image_url) {
                                                    $imageUrl = $item->menu->image_url;
                                                    $altText = $item->menu->name;
                                                } elseif ($item->menu && $item->menu->image) {
                                                    $imageUrl = asset('images/' . $item->menu->image);
                                                    $altText = $item->menu->name;
                                                } elseif ($item->menu_image) {
                                                    $imageUrl = asset('images/' . $item->menu_image);
                                                    $altText = $item->menu_name ?? 'Menu';
                                                }
                                            @endphp
                                            
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $altText }}" 
                                                     class="rounded shadow-sm" 
                                                     style="width: 55px; height: 55px; object-fit: cover;"
                                                     onerror="this.src='{{ asset('images/menu/americano.jpg') }}'">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                                     style="width: 55px; height: 55px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong class="text-dark">{{ $item->menu->name ?? $item->menu_name ?? 'Menu tidak tersedia' }}</strong>
                                                    <span class="badge bg-primary ms-2">{{ $item->quantity }}x</span>
                                                    @if($item->menu && $item->menu->category)
                                                        <br><small class="text-muted">
                                                            <i class="fas fa-tag"></i> {{ $item->menu->category }}
                                                        </small>
                                                    @endif
                                                    @if($item->preferences)
                                                        <br><small class="text-warning">
                                                            <i class="fas fa-star"></i> {{ $item->preferences }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold text-success">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                                    <small class="text-muted">@Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($order->notes)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-sticky-note"></i> <strong>Catatan:</strong> {{ $order->notes }}
                                    </small>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <!-- Order Summary -->
                                <div class="text-center mb-3">
                                    <h4 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Pesanan</small>
                                </div>

                                <!-- Order Timeline -->
                                <div class="timeline-sm mb-3">
                                    <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Pesanan Dibuat</span>
                                        @if($order->created_at)
                                            <small>{{ $order->created_at->format('H:i') }}</small>
                                        @endif
                                    </div>
                                    <div class="timeline-item {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'completed' : '' }}">
                                        <i class="fas fa-play-circle"></i>
                                        <span>Diproses</span>
                                    </div>
                                    <div class="timeline-item {{ in_array($order->status, ['ready', 'completed']) ? 'completed' : '' }}">
                                        <i class="fas fa-bell"></i>
                                        <span>Siap Diambil</span>
                                    </div>
                                    <div class="timeline-item {{ $order->status === 'completed' ? 'completed' : '' }}">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Selesai</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Detail Lengkap
                                    </a>
                                    
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <button type="button" class="btn btn-danger btn-sm" onclick="cancelOrder({{ $order->id }})">
                                            <i class="fas fa-times me-1"></i>Batalkan Pesanan
                                        </button>
                                    @endif
                                    
                                    @if($order->status === 'completed')
                                        <form action="{{ route('customer.orders.reorder', $order) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-redo me-1"></i>Pesan Lagi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-bag fa-4x text-muted"></i>
            </div>
            <h4>Belum Ada Pesanan</h4>
            @if($status || $period != '30')
                <p class="text-muted">Tidak ada pesanan yang sesuai dengan filter yang dipilih.</p>
                <button onclick="resetFilters()" class="btn btn-primary">Reset Filter</button>
            @else
                <p class="text-muted">Anda belum memiliki riwayat pesanan. Mulai berbelanja sekarang!</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-coffee me-2"></i>Mulai Berbelanja
                </a>
            @endif
        </div>
    @endif
</div>

<script>
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const period = document.getElementById('periodFilter').value;
    
    const url = new URL(window.location);
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    if (period) url.searchParams.set('period', period);
    else url.searchParams.delete('period');
    
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = '{{ route("customer.orders") }}';
}

// Cancel order function
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        fetch(`/customer/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to dashboard instead of reload
                window.location.href = '{{ route("customer.dashboard") }}';
            } else {
                alert(data.error || 'Terjadi kesalahan saat membatalkan pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membatalkan pesanan');
        });
    }
}

// Auto refresh for active orders
@if($stats['pending_orders'] > 0)
setInterval(() => {
    if (document.hidden) return; // Don't refresh if tab is not active
    
    fetch('{{ route("customer.orders") }}?ajax=1')
        .then(response => response.text())
        .then(html => {
            // Update only if there are changes in order status
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const currentStatuses = Array.from(document.querySelectorAll('.badge')).map(badge => badge.textContent);
            const newStatuses = Array.from(newDoc.querySelectorAll('.badge')).map(badge => badge.textContent);
            
            if (JSON.stringify(currentStatuses) !== JSON.stringify(newStatuses)) {
                location.reload();
            }
        })
        .catch(console.error);
}, 30000); // Check every 30 seconds
@endif
</script>

<style>
.order-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.timeline-sm {
    font-size: 0.85rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #6c757d;
}

.timeline-item.completed {
    color: #198754;
}

.timeline-item i {
    width: 20px;
    margin-right: 8px;
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
}

.order-items::-webkit-scrollbar {
    width: 4px;
}

.order-items::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.order-items::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.order-items::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.order-items .border-bottom:last-child {
    border-bottom: none !important;
}

@media (max-width: 768px) {
    .order-items {
        max-height: none;
    }
    
    .timeline-sm {
        display: none;
    }
}
</style>
@endsection
