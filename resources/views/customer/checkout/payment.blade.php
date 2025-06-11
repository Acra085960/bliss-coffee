<!-- resources/views/customer/checkout/payment.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Silakan Lanjutkan Pembayaran</h1>

        <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
        <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>

        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function () {
                snap.pay("{{ $snapToken }}", {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil");
                        console.log(result);
                    },
                    onPending: function(result) {
                        alert("Pembayaran tertunda");
                        console.log(result);
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal");
                        console.log(result);
                    }
                });
            }
        </script>
    </div>
@endsection
