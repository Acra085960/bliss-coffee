@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                <h1 class="text-success">Pesanan Berhasil!</h1>
                <p class="lead">Terima kasih atas pesanan Anda</p>
            </div>

            <!-- Order Details -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pesanan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td>Nomor Pesanan:</td>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Tanggal:</td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pembayaran:</td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Pelanggan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td>Nama:</td>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>Telepon:</td>
                                    <td>{{ $order->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $order->user->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($order->notes)
                    <div class="mt-3">
                        <h6>Catatan Pesanan</h6>
                        <p class="text-muted">{{ $order->notes }}</p>
                    </div>
                    @endif
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
                            <h6 class="mb-1">{{ $item->menu->name ?? 'Menu Item' }}</h6>
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
                    
                    <div class="text-end mt-3">
                        <h4 class="text-primary">
                            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Langkah Selanjutnya</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-primary me-3"></i>
                                <div>
                                    <strong>Estimasi Waktu</strong><br>
                                    <small class="text-muted">Pesanan akan siap dalam 10-15 menit</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-store text-primary me-3"></i>
                                <div>
                                    <strong>Pengambilan</strong><br>
                                    <small class="text-muted">Ambil pesanan di Bliss Coffee</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->payment_method === 'cash')
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Pembayaran Tunai:</strong> Silakan bayar saat pengambilan pesanan
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('customer.menu') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-coffee me-2"></i>Pesan Lagi
                </a>
                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-history me-2"></i>Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Auto redirect to orders page after 30 seconds
setTimeout(function() {
    if (confirm('Apakah Anda ingin melihat riwayat pesanan?')) {
        window.location.href = '{{ route("customer.orders") }}';
    }
}, 30000);
</script>
@endsection
