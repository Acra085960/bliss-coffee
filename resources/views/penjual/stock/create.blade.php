{{-- filepath: resources/views/penjual/stock/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Stok Bahan Baku</h3>
    <form action="{{ route('penjual.stock.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama Bahan Baku</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select name="category" id="category" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="current_stock" class="form-label">Stok Awal</label>
            <input type="number" name="current_stock" id="current_stock" class="form-control" min="0" required value="{{ old('current_stock', 0) }}">
        </div>

        <div class="mb-3">
            <label for="minimum_stock" class="form-label">Stok Minimum</label>
            <input type="number" name="minimum_stock" id="minimum_stock" class="form-control" min="0" required value="{{ old('minimum_stock', 0) }}">
        </div>

        <div class="mb-3">
            <label for="maximum_stock" class="form-label">Stok Maksimum</label>
            <input type="number" name="maximum_stock" id="maximum_stock" class="form-control" min="0" required value="{{ old('maximum_stock', 0) }}">
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Satuan</label>
            <select name="unit" id="unit" class="form-select" required>
                <option value="">-- Pilih Satuan --</option>
                @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price_per_unit" class="form-label">Harga per Satuan (opsional)</label>
            <input type="number" name="price_per_unit" id="price_per_unit" class="form-control" min="0" value="{{ old('price_per_unit') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi (opsional)</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('penjual.stock.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection