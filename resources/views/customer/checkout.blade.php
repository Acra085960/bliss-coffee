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
        .payment-option .card-body {
            padding: 0.7rem !important;
        }
        .payment-option .card {
            min-height: 90px;
        }
        .text-end, .text-right {
            text-align: left !important;
            margin-top: 1rem;
        }
        .position-sticky {
            position: static !important;
            top: unset !important;
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
        .btn.w-100, .btn-block {
            width: 100% !important;
        }
        .mt-3 {
            margin-top: 1rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Checkout</h1>
            <p class="text-muted">Periksa pesanan Anda dan pilih metode pembayaran</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', auth()->user()->name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Nomor Telepon *</label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone') }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Tambahkan catatan untuk pesanan Anda...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Metode Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check payment-option">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_cash" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="payment_cash">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                                <h6>Bayar Tunai</h6>
                                                <small class="text-muted">Bayar langsung saat pengambilan</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check payment-option">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_midtrans" value="midtrans" {{ old('payment_method') == 'midtrans' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="payment_midtrans">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                                                <h6>Bayar Online</h6>
                                                <small class="text-muted">Transfer Bank, E-Wallet, Kartu Kredit</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Item Pesanan ({{ $totalItems }} item)</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cart as $item)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="me-3">
                                @if($item['image'])
                                    <img src="{{ asset('images/'.$item['image']) }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item['name'] }}</h6>
                                @if(isset($item['preferences']) && $item['preferences'])
                                    <small class="text-muted">
                                        <i class="fas fa-star text-warning"></i> {{ $item['preferences'] }}
                                    </small>
                                @endif
                                <div class="text-muted small">
                                    {{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-end">
                                <strong>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endforeach
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
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $totalItems }} item)</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Layanan</span>
                            <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>

                        @if($tax > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak</span>
                            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Pembayaran</strong>
                            <strong class="text-primary h5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>

                        <!-- Order Info -->
                        <div class="bg-light p-3 rounded mb-3">
                            <h6>Informasi Pesanan</h6>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="fas fa-clock me-2"></i>Estimasi siap: 10-15 menit</li>
                                <li><i class="fas fa-store me-2"></i>Ambil di: Bliss Coffee</li>
                                <li><i class="fas fa-phone me-2"></i>Kontak: (021) 123-4567</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg" id="submitBtn">
                            <i class="fas fa-shopping-cart me-2"></i>
                            <span id="btnText">Buat Pesanan</span>
                            <span id="btnLoading" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Memproses...
                            </span>
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
});

// Enhanced payment option styling
document.querySelectorAll('.payment-option input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-option .card').forEach(card => {
            card.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
        });
        
        if (this.checked) {
            this.nextElementSibling.querySelector('.card').classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
        }
    });
});

// Set initial selection styling
document.addEventListener('DOMContentLoaded', function() {
    const checkedRadio = document.querySelector('.payment-option input[type="radio"]:checked');
    if (checkedRadio) {
        checkedRadio.nextElementSibling.querySelector('.card').classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    }
});
</script>

<style>
.payment-option {
    cursor: pointer;
}

.payment-option .form-check-input {
    display: none;
}

.payment-option .card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-option .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
