@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Pesanan</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <p class="text-muted">Berikut adalah riwayat pesanan Anda</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            <a href="{{ route('customer.test') }}" class="btn btn-outline-primary ms-2">Lihat Menu</a>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pesanan #{{ $order->id }}</h5>
                            <div>
                                <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : 'secondary')) }} fs-6 me-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                                @if($order->hasFeedback())
                                    <span class="badge bg-primary">Feedback Diberikan</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                                    <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                                    @if($order->notes)
                                        <p><strong>Catatan:</strong> {{ $order->notes }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6>Detail Pesanan:</h6>
                                    @if($order->orderItems->count() > 0)
                                        <ul class="list-unstyled">
                                            @foreach($order->orderItems as $item)
                                                <li class="mb-1">
                                                    {{ $item->menu->name ?? 'Menu tidak ditemukan' }} 
                                                    <span class="text-muted">({{ $item->quantity }}x)</span>
                                                    <span class="float-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Total:</strong>
                                            <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                        </div>
                                    @else
                                        <p class="text-muted">Detail pesanan tidak tersedia</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($order->status === 'pending')
                                <div class="mt-3">
                                    <small class="text-info">
                                        <i class="fas fa-clock"></i> Pesanan Anda sedang menunggu konfirmasi
                                    </small>
                                </div>
                            @elseif($order->status === 'processing')
                                <div class="mt-3">
                                    <small class="text-primary">
                                        <i class="fas fa-spinner"></i> Pesanan Anda sedang diproses
                                    </small>
                                </div>
                            @elseif($order->status === 'completed')
                                <div class="mt-3">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> Pesanan telah selesai
                                    </small>
                                </div>
                            @elseif($order->status === 'cancelled')
                                <div class="mt-3">
                                    <small class="text-danger">
                                        <i class="fas fa-times-circle"></i> Pesanan dibatalkan
                                    </small>
                                </div>
                            @endif

                            <!-- Feedback Section -->
                            @if($order->status === 'completed')
                                <div class="mt-3 pt-3 border-top">
                                    @if($order->hasFeedback())
                                        <div class="feedback-display">
                                            <h6 class="text-success">
                                                <i class="fas fa-check-circle"></i> Feedback Anda:
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Rating:</strong>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-warning">{{ $i <= $order->feedback->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            @if($order->feedback->comment)
                                                <p class="mb-0"><strong>Komentar:</strong> {{ $order->feedback->comment }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="feedback-prompt">
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-star"></i> Bagaimana pengalaman Anda dengan pesanan ini?
                                            </p>
                                            <a href="{{ route('customer.feedback.create', $order) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-comment"></i> Berikan Feedback
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-bag fa-4x text-muted"></i>
            </div>
            <h4>Belum Ada Pesanan</h4>
            <p class="text-muted">Anda belum memiliki riwayat pesanan. Mulai berbelanja sekarang!</p>
            <a href="{{ route('customer.test') }}" class="btn btn-primary btn-lg">Lihat Menu</a>
        </div>
    @endif
</div>
@endsection
