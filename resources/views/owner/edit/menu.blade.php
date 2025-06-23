{{-- filepath: /home/acra/bliss/resources/views/owner/edit/menu.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        h1 {
            font-size: 1.2rem;
        }
        .form-label {
            font-size: 1rem;
        }
        .form-control, .form-select {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .btn, .btn-primary, .btn-secondary {
            font-size: 1rem;
            padding: 0.7rem 1rem;
            width: 100%;
            margin-bottom: 0.7rem;
        }
        .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1>Edit Harga Menu</h1>
    <form action="{{ route('owner.menus.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $menu->category) }}" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $menu->price) }}" min="0" required>
        </div>
        <div class="mb-3">
            <label for="is_available" class="form-label">Status</label>
            <select class="form-select" id="is_available" name="is_available">
                <option value="1" {{ $menu->is_available ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ !$menu->is_available ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('owner.menus') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection