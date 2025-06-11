@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Keranjang Belanja</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach($cart as $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($item['image'])
                                        <img src="{{ asset('images/'.$item['image']) }}" class="img-fluid" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ $item['name'] }}</h5>
                                    <p>Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    <p>Jumlah: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p><strong>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong></p>
                                    <form action="{{ route('customer.cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Total Belanja</h5>
                    </div>
                    <div class="card-body">
                        <h4>Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        <a href="{{ route('customer.checkout') }}" class="btn btn-success w-100 mt-3">Checkout</a>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary w-100 mt-2">Lanjut Belanja</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <h4>Keranjang Kosong</h4>
            <p>Belum ada item dalam keranjang. <a href="{{ route('customer.dashboard') }}">Mulai belanja sekarang!</a></p>
        </div>
    @endif
</div>
@endsection
