@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1>Selamat datang, {{ auth()->user()->name }}!</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Order Tracking -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lacak Pesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.track-order') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="orderNumber" 
                                   placeholder="Masukkan nomor pesanan (contoh: BC-20231201-ABC123)">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i>Lacak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Recent active orders -->
            @php
                $activeOrders = \App\Models\Order::where('user_id', auth()->id())
                    ->whereIn('status', ['pending', 'processing', 'ready'])
                    ->latest()
                    ->limit(1)
                    ->first();
            @endphp
            
            @if($activeOrders)
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">Pesanan Aktif</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>#{{ $activeOrders->order_number }}</strong><br>
                            <span class="badge bg-{{ $activeOrders->status === 'ready' ? 'primary' : 'info' }}">
                                {{ ucfirst($activeOrders->status === 'ready' ? 'Siap Diambil' : $activeOrders->status) }}
                            </span>
                        </div>
                        <a href="{{ route('customer.track-order', $activeOrders->order_number) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Lacak
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Total Pesanan dan Pengeluaran -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Pesanan</div>
                <div class="card-body">
                    <p>{{ $totalOrders }} Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Pengeluaran</div>
                <div class="card-body">
                    <p>Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Keranjang</div>
                <div class="card-body">
                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-primary">Lihat Keranjang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pesanan Terbaru -->
    @if($recentOrders->count() > 0)
    <h2 class="mt-4">Pesanan Terbaru</h2>
    <div class="row">
        @foreach ($recentOrders as $order)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        Pesanan #{{ $order->id }}
                    </div>
                    <div class="card-body">
                        <p>Status: <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">{{ ucfirst($order->status) }}</span></p>
                        <p>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <a href="{{ route('customer.orders') }}" class="btn btn-primary">Lihat Pesanan</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Daftar Menu -->
    @if(isset($menus) && $menus->count() > 0)
    <h2 class="mt-4">Menu Kami</h2>
    <div class="row">
        @foreach ($menus as $menu)
            <div class="col-md-4">
                <div class="card mb-3">
                    @if($menu->image)
                        <img src="{{ asset('images/'.$menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $menu->name }}</h5>
                        <p class="card-text">{{ $menu->description ?? 'Deskripsi tidak tersedia' }}</p>
                        <p class="card-text"><strong>Rp {{ number_format($menu->price, 0, ',', '.') }}</strong></p>
                        
                        <!-- Form untuk menambah ke keranjang -->
                        <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-add-cart">
                                <span class="btn-text">Tambah ke Keranjang</span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Menambahkan...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info mt-4">
        <h4>Menu belum tersedia</h4>
        <p>Saat ini belum ada menu yang tersedia. Silakan cek kembali nanti.</p>
    </div>
    @endif

    <!-- Link ke halaman menu lengkap -->
    <div class="text-center mt-4">
        <a href="{{ route('customer.menu') }}" class="btn btn-outline-primary btn-lg">Lihat Semua Menu</a>
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
