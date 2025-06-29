@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container-fluid, .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1 {
            font-size: 1.1rem;
        }
        .row.mb-4 > [class^="col-"], .row.mb-4 > [class*=" col-"],
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
        .cart-item .row > div {
            margin-bottom: 10px;
        }
        .quantity-controls {
            margin-top: 10px;
        }
        .position-sticky {
            position: static !important;
            top: unset !important;
        }
        .btn.w-100, .btn-block {
            width: 100% !important;
        }
        .text-end, .text-right {
            text-align: left !important;
            margin-top: 1rem;
        }
    }
    /* Agar tabel atau card bisa discroll di layar kecil */
    .table-responsive, .container .table, .card-body.p-0 {
        overflow-x: auto;
        display: block;
    }
    @media (max-width: 767.98px) {
    .d-flex.flex-wrap.gap-2.justify-content-between,
    .d-flex.flex-wrap.gap-2.justify-content-md-end {
        flex-direction: column !important;
        gap: 0.7rem !important;
        align-items: stretch !important;
    }
    .w-100 {
        width: 100% !important;
    }
    .w-md-auto {
        width: 100% !important;
    }
}
@media (min-width: 768px) {
    .w-md-auto {
        width: auto !important;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Keranjang Belanja</h1>
            <p class="text-muted">Periksa item yang Anda pilih sebelum checkout</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('customer.menu') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
            </a>
        </div>
    </div>

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

    @if(!empty($cart) && count($cart) > 0)
        <div class="row">
            <div class="col-lg-8">
                <!-- Cart Items -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Item dalam Keranjang ({{ count($cart) }} item)</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cart as $key => $item)
                        <div class="cart-item border-bottom p-3" data-key="{{ $key }}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if($item['image'])
                                        <img src="{{ asset('images/'.$item['image']) }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="img-fluid rounded"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    @if(isset($item['category']))
                                        <span class="badge bg-secondary small">{{ $item['category'] }}</span>
                                    @endif
                                    @if(isset($item['preferences']))
                                        <div class="text-muted small mt-1">
                                            <i class="fas fa-star text-warning"></i> 
                                            {{ $item['preferences'] ?? '-' }}
                                            <!-- Tombol Edit Preferensi -->
                                            <button type="button" class="btn btn-link btn-sm p-0 ms-2" data-bs-toggle="modal" data-bs-target="#editPrefModal{{ $key }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <label class="form-label small">Harga</label>
                                        <div class="fw-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="quantity-controls">
                                        <label class="form-label small">Jumlah</label>
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity('{{ $key }}', -1)">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   value="{{ $item['quantity'] }}" min="1" max="10"
                                                   onchange="updateQuantityDirect('{{ $key }}', this.value)">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity('{{ $key }}', 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="text-center">
                                        <label class="form-label small">Subtotal</label>
                                        <div class="fw-bold text-primary item-subtotal">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="removeFromCart('{{ $key }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Modal Edit Preferensi -->
<div class="modal fade" id="editPrefModal{{ $key }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('customer.cart.updatePreference', $key) }}" method="POST" class="modal-content">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title">Edit Preferensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <select name="preferences" class="form-select">
                    <option value="">-- Pilih Preferensi --</option>
                    <option value="Less Sugar" {{ ($item['preferences'] ?? '')=='Less Sugar'?'selected':'' }}>Gula Sedikit</option>
                    <option value="No Sugar" {{ ($item['preferences'] ?? '')=='No Sugar'?'selected':'' }}>Tanpa Gula</option>
                    <option value="Extra Hot" {{ ($item['preferences'] ?? '')=='Extra Hot'?'selected':'' }}>Extra Panas</option>
                    <option value="Extra Ice" {{ ($item['preferences'] ?? '')=='Extra Ice'?'selected':'' }}>Ekstra Es</option>
                    <option value="Oat Milk" {{ ($item['preferences'] ?? '')=='Oat Milk'?'selected':'' }}>Susu Oat</option>
                    <option value="Almond Milk" {{ ($item['preferences'] ?? '')=='Almond Milk'?'selected':'' }}>Susu Almond</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
                        </div>
                        @endforeach
                    </div>
                </div>

               <!-- Cart Actions -->
<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between justify-content-md-end">
            <form action="{{ route('customer.cart.clear') }}" method="POST" 
                onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')" class="flex-grow-1">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 mb-2 mb-md-0">
                    <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                </button>
            </form>
            <a href="{{ route('customer.menu') }}" class="btn btn-outline-primary w-100 flex-grow-1">
                <i class="fas fa-plus me-2"></i>Tambah Item Lain
            </a>
        </div>
    </div>
</div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-summary">
                            @php
                                $subtotal = 0;
                                foreach($cart as $item) {
                                    $subtotal += $item['price'] * $item['quantity'];
                                }
                                $tax = 0; // No tax for now
                                $delivery = 0; // No delivery fee
                                $total = $subtotal + $tax + $delivery;
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ count($cart) }} item)</span>
                                <span id="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($tax > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak</span>
                                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            @if($delivery > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Biaya Antar</span>
                                <span>Rp {{ number_format($delivery, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <hr>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-primary h5" id="cart-total">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </strong>
                            </div>

                            <!-- Promo Code -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Kode promo">
                                    <button class="btn btn-outline-secondary" type="button">Gunakan</button>
                                </div>
                                <small class="text-muted">Masukkan kode promo jika ada</small>
                            </div>

                            <!-- Checkout Button -->
                            <a href="{{ route('customer.checkout') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6>Informasi Pesanan</h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li><i class="fas fa-clock me-2"></i>Estimasi siap: 10-15 menit</li>
                            <li><i class="fas fa-store me-2"></i>Ambil di toko</li>
                            <li><i class="fas fa-phone me-2"></i>Hubungi: (021) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h4>Keranjang Kosong</h4>
            <p class="text-muted mb-4">Anda belum menambahkan item apapun ke keranjang</p>
            <a href="{{ route('customer.menu') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-coffee me-2"></i>Mulai Berbelanja
            </a>
        </div>
    @endif
</div>

<!-- Remove Item Modal -->
<div class="modal fade" id="removeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus item ini dari keranjang?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="removeForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="key" id="removeKey">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateQuantity(key, change) {
    const input = document.querySelector(`[data-key="${key}"] .quantity-input`);
    let newValue = parseInt(input.value) + change;
    
    if (newValue < 1) newValue = 1;
    if (newValue > 10) newValue = 10;
    
    updateQuantityDirect(key, newValue);
}

function updateQuantityDirect(key, quantity) {
    if (quantity < 1 || quantity > 10) return;
    
    fetch('{{ route("customer.cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            key: key,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan saat mengupdate quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate quantity');
    });
}

function removeFromCart(key) {
    document.getElementById('removeKey').value = key;
    document.getElementById('removeForm').action = '{{ route("customer.cart.remove") }}';
    new bootstrap.Modal(document.getElementById('removeModal')).show();
}

// Auto-save quantity changes
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const key = this.closest('.cart-item').dataset.key;
                updateQuantityDirect(key, this.value);
            }, 1000);
        });
    });
});
</script>

<style>
.cart-item {
    transition: background-color 0.2s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
}

.quantity-controls .input-group {
    width: 120px;
}

.quantity-input {
    border-left: none;
    border-right: none;
}

.quantity-input:focus {
    box-shadow: none;
    border-color: #ced4da;
}

.item-subtotal {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .cart-item .row > div {
        margin-bottom: 10px;
    }
    
    .quantity-controls {
        margin-top: 10px;
    }
}
</style>
@endsection
