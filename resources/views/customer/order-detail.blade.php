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
        .row.mb-4 > [class^="col-"], .row.mb-4 > [class*=" col-"],
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .text-end, .text-right {
            text-align: left !important;
            margin-top: 1rem;
        }
        .card, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .btn, .btn-lg {
            font-size: 1rem;
            padding: 0.7rem 1rem;
        }
        .form-label, .form-control, .form-select {
            font-size: 1rem;
        }
        .d-flex.align-items-center.mb-3.pb-3.border-bottom {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .me-3 {
            margin-right: 0 !important;
            margin-bottom: 0.5rem !important;
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
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detail Pesanan #{{ $order->order_number }}</h1>
            <p class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
            </a>
            @if($order->status === 'completed')
                <form action="{{ route('customer.orders.reorder', $order) }}" method="POST" style="display: inline;" class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-redo me-2"></i>Pesan Lagi
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
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
                                     class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->menu->name ?? 'Menu tidak tersedia' }}</h6>
                            @if($item->menu && $item->menu->category)
                                <span class="badge bg-secondary">{{ $item->menu->category }}</span>
                            @endif
                            @if($item->preferences)
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-star text-warning"></i> {{ $item->preferences }}
                                </div>
                            @endif
                            <div class="text-muted small mt-1">
                                {{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="text-end">
                        <h4 class="text-primary mb-0">
                            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>

            @if($order->notes)
            <!-- Order Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Catatan Pesanan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body text-center">
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'ready' ? 'primary' : 'secondary'))) }} fs-5 p-3">
                        {{ ucfirst($order->status === 'ready' ? 'Siap Diambil' : $order->status) }}
                    </span>
                    
                    @if(isset($order->payment_status))
                    <div class="mt-3">
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }} fs-6">
                            Pembayaran: {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Nomor Pesanan:</td>
                            <td><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal Pesanan:</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($order->payment_method)
                        <tr>
                            <td>Metode Pembayaran:</td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                        </tr>
                        @endif
                        @if($order->paid_at)
                        <tr>
                            <td>Dibayar Pada:</td>
                            <td>{{ $order->paid_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Nama:</td>
                            <td>{{ $order->customer_name }}</td>
                        </tr>
                        @if($order->customer_phone)
                        <tr>
                            <td>Telepon:</td>
                            <td>{{ $order->customer_phone }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Email:</td>
                            <td>{{ $order->user->email }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Timeline Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Dibuat</strong><br>
                                <small>{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        @if(in_array($order->status, ['processing', 'ready', 'completed']))
                        <div class="timeline-item completed">
                            <i class="fas fa-play-circle text-info"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Diproses</strong><br>
                                <small>{{ $order->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($order->status, ['ready', 'completed']))
                        <div class="timeline-item completed">
                            <i class="fas fa-bell text-primary"></i>
                            <div class="timeline-content">
                                <strong>Siap Diambil</strong><br>
                                <small>{{ $order->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'completed')
                        <div class="timeline-item completed">
                            <i class="fas fa-check-circle text-success"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Selesai</strong><br>
                                <small>{{ $order->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'cancelled')
                        <div class="timeline-item">
                            <i class="fas fa-times-circle text-danger"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Dibatalkan</strong><br>
                                <small>{{ $order->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
}

.timeline-item i {
    position: absolute;
    left: -30px;
    top: 2px;
    font-size: 1.2em;
}

.timeline-content {
    margin-left: 10px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 10px;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item.completed i {
    color: #198754 !important;
}
</style>
@endsection
