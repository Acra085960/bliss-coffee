@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container-fluid, .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1, h5 {
            font-size: 1.1rem;
        }
        .lead, .text-muted {
            font-size: 1rem;
        }
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-header, .card-body {
            padding: 1rem;
        }
        .btn, .btn-lg {
            font-size: 1rem;
            padding: 0.7rem 1rem;
            width: 100%;
            margin-bottom: 0.7rem;
        }
        .btn:last-child {
            margin-bottom: 0;
        }
        .me-3 {
            margin-right: 0 !important;
            margin-bottom: 0.5rem !important;
        }
        .d-flex.align-items-center.mb-3.pb-3.border-bottom,
        .d-flex.align-items-center.mb-2 {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .text-end, .text-right {
            text-align: left !important;
            margin-top: 1rem;
        }
        .timeline {
            padding-left: 15px;
        }
        .timeline-item i {
            left: -20px;
        }
        .timeline::before {
            left: -14px;
        }
        .table {
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1>Lacak Pesanan</h1>
                <p class="text-muted">Pantau status pesanan Anda secara real-time</p>
            </div>

            <!-- Order Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Pesanan #{{ $order->order_number }}</h5>
                            <small>{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-light text-dark fs-6" id="lastUpdated">
                                Update terakhir: {{ $order->updated_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pelanggan</h6>
                            <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                            @if($order->customer_phone)
                                <p class="mb-1">{{ $order->customer_phone }}</p>
                            @endif
                            <p class="text-muted">{{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Pesanan</h6>
                            <h4 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                            @if(isset($order->payment_method))
                                <span class="badge bg-secondary">{{ ucfirst($order->payment_method) }}</span>
                            @endif
                            @if(isset($order->payment_status))
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Tracking -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <!-- Status Message -->
                    <div class="text-center mb-4">
                        <div class="status-icon mb-3" id="statusIcon">
                            @switch($order->status)
                                @case('pending')
                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                    @break
                                @case('processing')
                                    <i class="fas fa-spinner fa-3x text-info fa-spin"></i>
                                    @break
                                @case('ready')
                                    <i class="fas fa-bell fa-3x text-primary"></i>
                                    @break
                                @case('completed')
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    @break
                                @case('cancelled')
                                    <i class="fas fa-times-circle fa-3x text-danger"></i>
                                    @break
                            @endswitch
                        </div>
                        <h4 id="statusMessage">
                            @switch($order->status)
                                @case('pending')
                                    Pesanan Anda sedang menunggu konfirmasi
                                    @break
                                @case('processing')
                                    Pesanan Anda sedang disiapkan
                                    @break
                                @case('ready')
                                    Pesanan Anda siap diambil!
                                    @break
                                @case('completed')
                                    Pesanan Anda telah selesai
                                    @break
                                @case('cancelled')
                                    Pesanan Anda telah dibatalkan
                                    @break
                            @endswitch
                        </h4>
                        <p class="text-muted" id="estimatedTime">
                            Estimasi: 
                            @switch($order->status)
                                @case('pending')
                                    15-20 menit
                                    @break
                                @case('processing')
                                    10-15 menit
                                    @break
                                @case('ready')
                                    Siap diambil
                                    @break
                                @case('completed')
                                    Selesai
                                    @break
                                @case('cancelled')
                                    Dibatalkan
                                    @break
                            @endswitch
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             id="progressBar"
                             style="width: {{ 
                                $order->status === 'pending' ? '25' : 
                                ($order->status === 'processing' ? '50' : 
                                ($order->status === 'ready' ? '75' : 
                                ($order->status === 'completed' ? '100' : '0'))) 
                             }}%">
                            <span class="progress-text">
                                {{ ucfirst($order->status === 'ready' ? 'Siap' : $order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline">
                        <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Pesanan Dibuat</h6>
                                <small>{{ $order->created_at->format('H:i') }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Sedang Diproses</h6>
                                <small id="processingTime">
                                    {{ in_array($order->status, ['processing', 'ready', 'completed']) ? $order->updated_at->format('H:i') : '-' }}
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($order->status, ['ready', 'completed']) ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Siap Diambil</h6>
                                <small id="readyTime">
                                    {{ in_array($order->status, ['ready', 'completed']) ? $order->updated_at->format('H:i') : '-' }}
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item {{ $order->status === 'completed' ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Selesai</h6>
                                <small id="completedTime">
                                    {{ $order->status === 'completed' ? $order->updated_at->format('H:i') : '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            @if($item->menu && $item->menu->image)
                                <img src="{{ asset('images/'.$item->menu->image) }}" 
                                     alt="{{ $item->menu->name }}" 
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->menu->name ?? 'Menu tidak tersedia' }}</h6>
                            @if($item->preferences)
                                <small class="text-muted">
                                    <i class="fas fa-star text-warning"></i> {{ $item->preferences }}
                                </small>
                            @endif
                            <div class="text-muted small">
                                {{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="text-end">
                            <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                @if(in_array($order->status, ['pending', 'processing']))
                    <button class="btn btn-danger me-3" onclick="cancelOrder()">
                        <i class="fas fa-times me-2"></i>Batalkan Pesanan
                    </button>
                @endif
                
                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-list me-2"></i>Lihat Semua Pesanan
                </a>
                
                @if($order->status === 'completed')
                    <form action="{{ route('customer.orders.reorder', $order) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-redo me-2"></i>Pesan Lagi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast hide" role="alert">
        <div class="toast-header">
            <i class="fas fa-sync-alt text-primary me-2"></i>
            <strong class="me-auto">Status Update</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Status pesanan telah diperbarui!
        </div>
    </div>
</div>

<script>
let currentStatus = '{{ $order->status }}';
let orderNumber = '{{ $order->order_number }}';

// Auto-refresh status every 10 seconds for active orders
let refreshInterval;
if (['pending', 'processing', 'ready'].includes(currentStatus)) {
    refreshInterval = setInterval(checkOrderStatus, 10000);
}

function checkOrderStatus() {
    fetch(`/customer/orders/status?order_number=${orderNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }
            
            if (data.status !== currentStatus) {
                updateOrderStatus(data);
                showStatusUpdate(data.status_message);
                currentStatus = data.status;
                
                // Stop auto-refresh if order is completed or cancelled
                if (['completed', 'cancelled'].includes(data.status)) {
                    clearInterval(refreshInterval);
                }
            }
            
            // Update last updated time
            document.getElementById('lastUpdated').textContent = `Update terakhir: ${data.updated_at}`;
        })
        .catch(error => {
            console.error('Error checking status:', error);
        });
}

function updateOrderStatus(data) {
    // Update status icon
    const statusIcon = document.getElementById('statusIcon');
    const iconMap = {
        'pending': '<i class="fas fa-clock fa-3x text-warning"></i>',
        'processing': '<i class="fas fa-spinner fa-3x text-info fa-spin"></i>',
        'ready': '<i class="fas fa-bell fa-3x text-primary"></i>',
        'completed': '<i class="fas fa-check-circle fa-3x text-success"></i>',
        'cancelled': '<i class="fas fa-times-circle fa-3x text-danger"></i>'
    };
    statusIcon.innerHTML = iconMap[data.status] || iconMap['pending'];
    
    // Update status message
    document.getElementById('statusMessage').textContent = data.status_message;
    document.getElementById('estimatedTime').textContent = `Estimasi: ${data.estimated_time}`;
    
    // Update progress bar
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = `${data.progress_percentage}%`;
    progressBar.querySelector('.progress-text').textContent = data.status === 'ready' ? 'Siap' : data.status;
    
    // Update timeline
    updateTimeline(data.status);
    
    // Hide cancel button if order cannot be cancelled anymore
    if (!data.can_cancel) {
        const cancelBtn = document.querySelector('button[onclick="cancelOrder()"]');
        if (cancelBtn) cancelBtn.style.display = 'none';
    }
}

function updateTimeline(status) {
    const timelineItems = document.querySelectorAll('.timeline-item');
    const statusOrder = ['pending', 'processing', 'ready', 'completed'];
    const currentIndex = statusOrder.indexOf(status);
    
    timelineItems.forEach((item, index) => {
        if (index <= currentIndex) {
            item.classList.add('completed');
        } else {
            item.classList.remove('completed');
        }
    });
}

function showStatusUpdate(message) {
    const toast = new bootstrap.Toast(document.getElementById('liveToast'));
    document.getElementById('toastMessage').textContent = message;
    toast.show();
}

function cancelOrder() {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        fetch(`/customer/orders/{{ $order->id }}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
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

// Cleanup interval when page is unloaded
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.timeline-item.completed {
    opacity: 1;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-marker {
    background: #198754;
    border-color: #198754;
    color: white;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -25px;
    top: 15px;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.progress-text {
    font-weight: bold;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.status-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    border-bottom: none;
}
</style>
@endsection
