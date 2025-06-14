<!-- resources/views/customer/feedback/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Berikan Feedback</h1>

        <form action="{{ route('customer.feedback.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label for="comments">Komentar</label>
                <textarea name="comments" id="comments" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Feedback</button>
        </form>
    </div>
@endsection
