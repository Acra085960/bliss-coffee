@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_name">Nama Pelanggan</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', auth()->user()->name) }}" required>
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_phone">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone') }}" required>
                                    @error('customer_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="notes">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Catatan khusus untuk pesanan Anda">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg">Buat Pesanan</button>
                        <a href="{{ route('customer.cart') }}" class="btn btn-secondary btn-lg">Kembali ke Keranjang</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item['name'] }} ({{ $item['quantity'] }}x)</span>
                            <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
