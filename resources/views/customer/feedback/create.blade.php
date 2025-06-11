@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Berikan Feedback untuk Pesanan #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6>Detail Pesanan:</h6>
                        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <div>
                            <strong>Item:</strong>
                            <ul class="mb-0">
                                @foreach($order->orderItems as $item)
                                    <li>{{ $item->menu->name ?? 'Menu tidak ditemukan' }} ({{ $item->quantity }}x)</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Feedback Form -->
                    <form action="{{ route('customer.feedback.store', $order) }}" method="POST">
                        @csrf
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Rating *</strong></label>
                            <div class="rating-container">
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" class="star">★</label>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label for="comment" class="form-label"><strong>Komentar (Opsional)</strong></label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Bagikan pengalaman Anda dengan pesanan ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.orders') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
}

.star-rating input {
    display: none;
}

.star-rating label {
    color: #ddd;
    font-size: 2rem;
    cursor: pointer;
    transition: color 0.3s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}

.star-rating input:checked ~ label {
    color: #ffc107;
}
</style>
@endsection
