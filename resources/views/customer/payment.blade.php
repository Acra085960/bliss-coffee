@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container-fluid, .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .card, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-header, .card-body {
            padding: 1rem;
        }
        h5, h4 {
            font-size: 1.1rem;
        }
        .btn, .btn-lg {
            font-size: 1rem;
            padding: 0.7rem 1rem;
            width: 100%;
        }
        .mb-4 {
            margin-bottom: 1rem !important;
        }
        .fa-4x {
            font-size: 2.5rem !important;
        }
        .text-center {
            text-align: center !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pembayaran Online</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-credit-card fa-4x text-primary mb-3"></i>
                        <h4>Pesanan #{{ $order->order_number }}</h4>
                        <p class="text-muted">Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                    </div>
                    
                    <button id="pay-button" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock me-2"></i>Bayar Sekarang
                    </button>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            Pembayaran aman melalui Midtrans<br>
                            Mendukung berbagai metode pembayaran
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
document.getElementById('pay-button').onclick = function(){
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            alert("Pembayaran berhasil!");
            console.log(result);
            window.location.href = '{{ route("customer.order-success", $order->id) }}';
        },
        onPending: function(result){
            alert("Menunggu pembayaran!");
            console.log(result);
            window.location.href = '{{ route("customer.orders") }}';
        },
        onError: function(result){
            alert("Pembayaran gagal!");
            console.log(result);
            window.location.href = '{{ route("customer.checkout") }}';
        }
    });
};
</script>
@endsection
