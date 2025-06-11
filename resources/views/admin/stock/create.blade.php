{{-- filepath: /home/acra/bliss/resources/views/admin/stock/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-semibold mb-8 ml-12">Buat Stock Baru</h1>

    <form action="{{ route('penjual.stock.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-lg rounded-lg p-10 ml-12 max-w-2xl">
            <div class="p-4">
                <!-- Nama Barang -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-base font-medium text-gray-700 mb-2">Nama Barang</label>
                    </div>
                    <div>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="block w-full px-4 py-3 border-2 border-blue-700 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-700 bg-white text-gray-900 font-semibold text-lg mb-2"
                            required
                        >
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Jumlah (Quantity) -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block text-base font-medium text-gray-700 mb-2">Jumlah</label>
                    </div>
                    <div>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            class="block w-full px-4 py-3 border-2 border-blue-700 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-700 bg-white text-gray-900 font-semibold text-lg mb-2"
                            required
                        >
                        @error('quantity')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Satuan (Unit) -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="unit" class="block text-base font-medium text-gray-700 mb-2">Satuan</label>
                    </div>
                    <div>
                        <input
                            type="text"
                            id="unit"
                            name="unit"
                            value="{{ old('unit') }}"
                            class="block w-full px-4 py-3 border-2 border-blue-700 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-700 bg-white text-gray-900 font-semibold text-lg mb-2"
                            required
                        >
                        @error('unit')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="description" class="block text-base font-medium text-gray-700 mb-2">Deskripsi</label>
                    </div>
                    <div>
                        <textarea
                            id="description"
                            name="description"
                            class="block w-full px-4 py-3 border-2 border-blue-700 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-700 bg-white text-gray-900 font-semibold text-lg mb-2"
                            rows="3"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Simpan dan Kembali -->
                <div class="flex justify-between mt-8 gap-6">
                    <button type="submit" class="btn btn-primary w-1/2 py-2 px-2 text-lg rounded-lg">
                        <i class="fas fa-save mr-2"></i> Simpan Stock
                    </button>
                    <a href="{{ route('penjual.stock.index') }}" class="btn btn-secondary w-1/2 py-2 px-3 text-lg rounded-lg text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection