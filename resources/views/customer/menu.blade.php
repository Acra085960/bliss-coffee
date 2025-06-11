@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Menu Bliss Coffee</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <p class="lead">Nikmati berbagai pilihan kopi dan minuman terbaik kami</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('customer.cart') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Lihat Keranjang
            </a>
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-history"></i> Riwayat Pesanan
            </a>
        </div>
    </div>

    @if($menus->count() > 0)
        <div class="row">
            @foreach ($menus as $menu)
                <div class="col-md-4 col-lg-3">
                    <div class="card mb-4">
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
                            
                            @if($menu->is_available)
                                <!-- Form untuk menambah ke keranjang -->
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary w-100 btn-add-cart">
                                        <span class="btn-text">Tambah ke Keranjang</span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Menambahkan...
                                        </span>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $menus->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <h4>Menu Belum Tersedia</h4>
            <p>Saat ini belum ada menu yang tersedia. Silakan cek kembali nanti.</p>
        </div>
    @endif

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
</div>
@endsection
