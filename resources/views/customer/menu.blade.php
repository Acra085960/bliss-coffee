{{-- filepath: /home/acra/bliss/resources/views/customer/menu.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container-fluid, .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1, h3 {
            font-size: 1.1rem;
        }
        .lead {
            font-size: 1rem;
        }
        .row > [class^="col-"], .row > [class*=" col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        .card, .menu-card {
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
        .card-img-top, .bg-light.d-flex {
            height: 140px !important;
        }
        .d-flex.flex-wrap.gap-2 {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        .add-to-cart-form .btn {
            width: 100%;
        }
        .d-flex.justify-content-between, .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        .text-end {
            text-align: left !important;
            margin-top: 1rem;
        }
        .pagination {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

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
                        <div class="col-md-6">
                            <label class="form-label">Cari Menu</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ $search }}" placeholder="Cari nama menu atau deskripsi...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter Kategori</label>
                            <select class="form-select" name="category">
                                <option value="">Semua Kategori</option>
                                <option value="Kopi Panas" {{ $category == 'Kopi Panas' ? 'selected' : '' }}>Kopi Panas</option>
                                <option value="Kopi Dingin" {{ $category == 'Kopi Dingin' ? 'selected' : '' }}>Kopi Dingin</option>
                                <option value="Non-Kopi" {{ $category == 'Non-Kopi' ? 'selected' : '' }}>Non-Kopi</option>
                                <option value="Makanan" {{ $category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2 w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                    @if($search || $category)
                    <div class="mt-2">
                        <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Reset Filter
                        </a>
                    </div>
                    @endif
                </div>
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
            $categoryOrder = ['Kopi Panas', 'Kopi Dingin', 'Non-Kopi', 'Makanan'];
            $orderedCategories = collect($categoryOrder)->intersect($menusByCategory->keys())
                               ->merge($menusByCategory->keys()->diff($categoryOrder));
        @endphp

        @foreach($orderedCategories as $categoryName)
        @php $categoryMenus = $menusByCategory[$categoryName]; @endphp
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
                        <img src="{{ $menu->image_url }}" 
                             class="card-img-top" 
                             alt="{{ $menu->name }}" 
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/menu/americano.jpg') }}'">
                        
                        <!-- Stock Status Badge -->
                        <span class="position-absolute top-0 end-0 m-2">
                            @php
                                $stockAvailability = $menu->checkStockAvailability(1);
                                $stockStatus = $stockAvailability['stock_status'];
                                $maxQuantity = $stockAvailability['max_quantity'];
                            @endphp
                            
                            @if($stockStatus === 'out_of_stock')
                                <span class="badge bg-danger">Habis</span>
                            @elseif($stockStatus === 'low_stock')
                                <span class="badge bg-warning text-dark">Stok Sedikit ({{ $maxQuantity }})</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $menu->name }}</h5>
                        
                        <p class="card-text text-muted flex-grow-1">
                            {{ $menu->description ? Str::limit($menu->description, 80) : 'Deskripsi tidak tersedia' }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="mb-3">
                                <h4 class="text-primary mb-0">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </h4>
                            </div>
                            
                            <!-- Stock Info & Add to Cart Form -->
                            @php
                                $stockAvailability = $menu->checkStockAvailability(1);
                                $canOrder = $stockAvailability['can_make'];
                                $maxQuantity = $stockAvailability['max_quantity'];
                                $missingIngredients = $stockAvailability['missing_ingredients'];
                            @endphp
                            
                            @if($canOrder)
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    
                                    <!-- Stock Info -->
                                    @if(in_array($menu->category, ['Kopi Dingin', 'Kopi Panas', 'Non-Kopi', 'Makanan']) && $maxQuantity <= 10)
                                        <div class="alert alert-warning alert-sm py-1 px-2 mb-2">
                                            <small><i class="fas fa-info-circle"></i> Max tersedia: {{ $maxQuantity }} porsi</small>
                                        </div>
                                    @endif
                                    
                                    <!-- Quantity and Preferences -->
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small">Jumlah</label>
                                            <select class="form-select form-select-sm" name="quantity">
                                                @php
                                                    $maxAllowed = in_array($menu->category, ['Kopi Dingin', 'Kopi Panas', 'Non-Kopi', 'Makanan']) ? min(10, $maxQuantity) : 10;
                                                @endphp
                                                @for($i = 1; $i <= $maxAllowed; $i++)
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
                            @else
                                <!-- Out of Stock Message -->
                                <div class="alert alert-danger alert-sm py-2 px-3 mb-2">
                                    <small><i class="fas fa-exclamation-triangle"></i> Stok habis</small>
                                    @if(!empty($missingIngredients))
                                        <br><small class="text-muted">Kurang: {{ implode(', ', array_column($missingIngredients, 'name')) }}</small>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-times me-1"></i>Tidak Tersedia
                                </button>
                            @endif
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