@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Detail Pesanan #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjual.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->menu->name ?? 'Menu tidak ditemukan' }}</strong>
                                        @if($item->menu && $item->menu->description)
                                            <br><small class="text-muted">{{ Str::limit($item->menu->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3">Total Pesanan</th>
                                    <th>Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->notes)
            <!-- Order Notes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Catatan Pesanan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body text-center">
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'secondary')) }} fs-5 p-3">
                        {{ ucfirst($order->status) }}
                    </span>
                    
                    <div class="mt-3">
                        @if($order->status === 'pending')
                            <button class="btn btn-info w-100 mb-2" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                <i class="fas fa-play me-2"></i>Mulai Proses
                            </button>
                        @elseif($order->status === 'processing')
                            <button class="btn btn-success w-100 mb-2" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                <i class="fas fa-check me-2"></i>Tandai Selesai
                            </button>
                        @endif
                        
                        @if($order->status !== 'completed' && $order->status !== 'cancelled')
                            <button class="btn btn-danger w-100" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                <i class="fas fa-times me-2"></i>Batalkan Pesanan
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Nama:</strong><br>
                        {{ $order->customer_name ?? $order->user->name ?? 'N/A' }}
                    </div>
                    @if($order->customer_phone)
                    <div class="mb-2">
                        <strong>Telepon:</strong><br>
                        {{ $order->customer_phone }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Email:</strong><br>
                        {{ $order->user->email ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Timeline Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Dibuat</strong><br>
                                <small>{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($order->status === 'processing' || $order->status === 'completed')
                        <div class="timeline-item">
                            <i class="fas fa-play-circle text-info"></i>
                            <div class="timeline-content">
                                <strong>Pesanan Diproses</strong><br>
                                <small>{{ $order->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'completed')
                        <div class="timeline-item">
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

<script>
function updateOrderStatus(orderId, status) {
    const statusText = {
        'processing': 'memproses',
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
</script>

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
</style>
@endsection
