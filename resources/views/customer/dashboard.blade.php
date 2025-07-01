{{-- filepath: /home/acra/bliss/resources/views/customer/dashboard.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1, h2 {
            font-size: 1.2rem;
        }
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .card.mb-3, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .btn, .btn-sm, .btn-lg {
            font-size: 0.98rem;
            padding: 0.6rem 1rem;
        }
        .input-group {
            flex-direction: column;
        }
        .input-group .form-control, .input-group .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .card-img-top, .bg-light.d-flex {
            height: 150px !important;
        }
        .add-to-cart-form .btn {
            width: 100%;
        }
        .text-center.mt-4 {
            margin-top: 1.5rem !important;
        }
    }
    /* Agar tabel bisa discroll di layar kecil */
    .table-responsive, .container .table {
        overflow-x: auto;
        display: block;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <h1>Selamat datang, {{ auth()->user()->name }}!</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tracking Pesanan yang Sedang Dilacak -->
    @if(isset($isTracking) && $isTracking && isset($trackedOrder))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-info" style="border-left: 5px solid #0dcaf0;">
                <h5><i class="fas fa-search me-2"></i>Pesanan yang Anda Lacak</h5>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nomor Pesanan:</strong> {{ $trackedOrder->order_number }}<br>
                        <strong>Tanggal:</strong> {{ $trackedOrder->created_at->format('d M Y, H:i') }}<br>
                        <strong>Total:</strong> Rp {{ number_format($trackedOrder->total_price, 0, ',', '.') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> 
                        <span class="badge 
                            @if($trackedOrder->status == 'pending') bg-warning text-dark
                            @elseif($trackedOrder->status == 'processing') bg-info
                            @elseif($trackedOrder->status == 'ready') bg-success
                            @elseif($trackedOrder->status == 'completed') bg-success
                            @else bg-danger
                            @endif">
                            {{ ucfirst($trackedOrder->status) }}
                        </span><br>
                        <strong>Pembayaran:</strong> 
                        <span class="badge {{ $trackedOrder->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($trackedOrder->payment_status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                <hr>
                <h6>Item Pesanan:</h6>
                <ul class="mb-0">
                    @foreach($trackedOrder->orderItems as $item)
                    <li>{{ $item->menu->name ?? 'Menu tidak ditemukan' }} - {{ $item->quantity }}x 
                        (Rp {{ number_format($item->price, 0, ',', '.') }})</li>
                    @endforeach
                </ul>
                <div class="mt-3">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
                    </a>
                    
                    @if(in_array($trackedOrder->status, ['pending']))
                    <form action="{{ route('customer.orders.cancel', $trackedOrder->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            <i class="fas fa-times me-1"></i>Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Order Tracking Form -->
    @if(!isset($isTracking) || !$isTracking)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-search me-2"></i>Lacak Pesanan
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.track-order') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="order_number" class="form-control" 
                                   placeholder="Masukkan nomor pesanan..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i>Lacak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Total Pesanan dan Pengeluaran -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-bag me-2"></i>Total Pesanan
                </div>
                <div class="card-body">
                    <h4 class="text-primary">{{ $totalOrders }}</h4>
                    <p class="text-muted mb-0">Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-2"></i>Total Pengeluaran
                </div>
                <div class="card-body">
                    <h4 class="text-success">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                    <p class="text-muted mb-0">Total Belanja</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-2"></i>Keranjang
                </div>
                <div class="card-body">
                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Aktif -->
    @php
        $activeOrders = \App\Models\Order::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->latest()
            ->limit(3)
            ->get();
    @endphp

    @if($activeOrders->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Pesanan Aktif</h6>
                </div>
                <div class="card-body">
                    @foreach($activeOrders as $activeOrder)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 
                        @if(isset($trackedOrder) && $trackedOrder->id == $activeOrder->id) 
                            border border-primary bg-light rounded
                        @endif">
                        <div>
                            <strong>{{ $activeOrder->order_number }}</strong><br>
                            <small class="text-muted">{{ $activeOrder->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge 
                                @if($activeOrder->status == 'pending') bg-warning text-dark
                                @elseif($activeOrder->status == 'processing') bg-info
                                @elseif($activeOrder->status == 'ready') bg-success
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($activeOrder->status) }}
                            </span><br>
                            <small>Rp {{ number_format($activeOrder->total_price, 0, ',', '.') }}</small><br>
                            
                            <div class="mt-2">
                                <a href="{{ route('customer.track-order', $activeOrder->order_number) }}" 
                                   class="btn btn-sm btn-outline-primary">Lacak</a>
                                
                                @if($activeOrder->status == 'pending')
                                <form action="{{ route('customer.orders.cancel', $activeOrder->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Batalkan pesanan {{ $activeOrder->order_number }}?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Pesanan Terbaru -->
    @if($recentOrders->count() > 0)
    <h2 class="mt-4 mb-3">
        <i class="fas fa-history me-2"></i>Pesanan Terbaru
    </h2>
    <div class="row">
        @foreach ($recentOrders as $order)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ $order->order_number }}</span>
                        <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge 
                                @if($order->status === 'completed') bg-success
                                @elseif($order->status === 'pending') bg-warning text-dark
                                @elseif($order->status === 'processing') bg-info
                                @elseif($order->status === 'ready') bg-success
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p class="mb-2">
                            <strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i>Detail
                            </a>
                            <a href="{{ route('customer.track-order', $order->order_number) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-search me-1"></i>Lacak
                            </a>
                            
                            @if($order->status == 'pending')
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Batalkan pesanan {{ $order->order_number }}?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info mt-4">
        <h4><i class="fas fa-info-circle me-2"></i>Belum Ada Pesanan</h4>
        <p class="mb-0">Anda belum memiliki pesanan. Silakan pesan menu favorit Anda!</p>
    </div>
    @endif

    <!-- Daftar Menu -->
    @if(isset($menus) && $menus->count() > 0)
    <h2 class="mt-4 mb-3">
        <i class="fas fa-utensils me-2"></i>Menu Kami
    </h2>
    <div class="row">
        @foreach ($menus as $menu)
            <div class="col-md-4">
                <div class="card mb-3">
                    @if($menu->image)
                        <img src="{{ asset('images/'.$menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-muted">
                                <i class="fas fa-image fa-3x"></i><br>
                                No Image
                            </span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $menu->name }}</h5>
                        <p class="card-text">{{ $menu->description ?? 'Deskripsi tidak tersedia' }}</p>
                        <p class="card-text">
                            <strong class="text-success">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
                        </p>
                        
                        @if($menu->is_available)
                        <!-- Form untuk menambah ke keranjang -->
                        <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-add-cart">
                                <span class="btn-text">
                                    <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Menambahkan...
                                </span>
                            </button>
                        </form>
                        @else
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-times me-1"></i>Tidak Tersedia
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info mt-4">
        <h4><i class="fas fa-utensils me-2"></i>Menu belum tersedia</h4>
        <p class="mb-0">Saat ini belum ada menu yang tersedia. Silakan cek kembali nanti.</p>
    </div>
    @endif

    <!-- Link ke halaman menu lengkap -->
    <div class="text-center mt-4 mb-4">
        <a href="{{ route('customer.menu') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-list me-2"></i>Lihat Semua Menu
        </a>
        <a href="{{ route('customer.orders') }}" class="btn btn-outline-info btn-lg ms-2">
            <i class="fas fa-history me-2"></i>Riwayat Pesanan
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle add to cart form submissions
    const forms = document.querySelectorAll('.add-to-cart-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('.btn-add-cart');
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');
            
            // Show loading state
            button.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            
            // Re-enable button after 3 seconds (fallback)
            setTimeout(() => {
                button.disabled = false;
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }, 3000);
        });
    });
});
</script>
@endsection