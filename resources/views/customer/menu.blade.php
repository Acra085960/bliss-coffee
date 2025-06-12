{{-- filepath: /home/acra/bliss/resources/views/customer/menu.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Menu Bliss Coffee</h1>
            <p class="lead text-muted">Nikmati berbagai pilihan kopi dan minuman terbaik kami</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('customer.cart') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Keranjang
                <span class="badge bg-light text-dark ms-1" id="cartCount">{{ session()->get('cart') ? count(session()->get('cart')) : 0 }}</span>
            </a>
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('customer.menu') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari Menu</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ $search }}" placeholder="Cari nama menu atau deskripsi...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="category">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Quick Filter -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('customer.menu') }}" 
                   class="btn btn-outline-primary {{ !$category ? 'active' : '' }}">
                    Semua Menu
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('customer.menu', ['category' => $cat]) }}" 
                       class="btn btn-outline-primary {{ $category == $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($menus->count() > 0)
        <!-- Current Filter Info -->
        @if($category || $search)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-filter"></i> 
                    Menampilkan {{ $menus->total() }} menu
                    @if($category) dalam kategori "<strong>{{ $category }}</strong>" @endif
                    @if($search) dengan pencarian "<strong>{{ $search }}</strong>" @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Menu Items by Category -->
        @php
            $menusByCategory = $menus->groupBy('category');
        @endphp

        @foreach($menusByCategory as $categoryName => $categoryMenus)
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-coffee text-primary me-2"></i>{{ $categoryName }}
                    <span class="badge bg-primary ms-2">{{ $categoryMenus->count() }} item</span>
                </h3>
            </div>
            
            @foreach($categoryMenus as $menu)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="card h-100 menu-card">
                    <div class="position-relative">
                        @if($menu->image)
                            <img src="{{ asset('images/'.$menu->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $menu->name }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No Image</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <span class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary">{{ $menu->category }}</span>
                        </span>
                        
                        <!-- Availability Badge -->
                        <span class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">Tersedia</span>
                        </span>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $menu->name }}</h5>
                        
                        <p class="card-text text-muted flex-grow-1">
                            {{ $menu->description ? Str::limit($menu->description, 80) : 'Deskripsi tidak tersedia' }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </h4>
                                <div class="rating text-warning">
                                    <!-- Static rating for demo - could be dynamic -->
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    <small class="text-muted">(4.8)</small>
                                </div>
                            </div>
                            
                            <!-- Add to Cart Form -->
                            <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                
                                <!-- Quantity and Preferences -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small">Jumlah</label>
                                        <select class="form-select form-select-sm" name="quantity">
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Preferensi</label>
                                        <select class="form-select form-select-sm" name="preferences">
                                            <option value="">Standar</option>
                                            <option value="Less Sugar">Gula Sedikit</option>
                                            <option value="No Sugar">Tanpa Gula</option>
                                            <option value="Extra Hot">Extra Panas</option>
                                            <option value="Iced">Es</option>
                                            <option value="Oat Milk">Susu Oat</option>
                                            <option value="Almond Milk">Susu Almond</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 btn-add-cart">
                                    <span class="btn-text">
                                        <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Menambahkan...
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $menus->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted"></i>
            </div>
            <h4>Menu Tidak Ditemukan</h4>
            @if($search || $category)
                <p class="text-muted">
                    Tidak ada menu yang sesuai dengan pencarian atau filter Anda.
                </p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary">
                    <i class="fas fa-times me-1"></i>Reset Filter
                </a>
            @else
                <p class="text-muted">Saat ini belum ada menu yang tersedia.</p>
            @endif
        </div>
    @endif
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
            
            // Re-enable button after response
            setTimeout(() => {
                button.disabled = false;
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }, 2000);
        });
    });
    
    // Update cart count when items are added
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                // This would be better with AJAX, but for now just reload count
                location.reload();
            }, 2500);
        });
    });
});
</script>

<style>
.menu-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e0e0e0;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-outline-primary.active {
    background-color: var(--bs-primary);
    color: white;
}

.rating .fas {
    font-size: 0.8rem;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.menu-card:hover .card-img-top {
    transform: scale(1.05);
}

.btn-add-cart {
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection