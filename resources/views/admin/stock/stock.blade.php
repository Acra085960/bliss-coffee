{{-- filepath: /home/acra/bliss/resources/views/admin/stock/stock.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-semibold mb-8 ml-12">Daftar Stok Barang</h1>

    <!-- Add Stock Button -->
    <div class="flex justify-end mb-6 mr-12">
        <a href="{{ route('penjual.stock.create') }}" class="btn btn-primary py-2 px-4 rounded-lg">
            <i class="fas fa-plus-circle mr-2"></i> Buat Stock Baru
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-10 ml-12 max-w-4xl">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-4 font-medium text-sm text-gray-700">Nama Barang</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Jumlah</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Satuan</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Deskripsi</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse ($stocks as $stock)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $stock->name }}</td>
                    <td class="p-4">{{ $stock->quantity }}</td>
                    <td class="p-4">{{ $stock->unit }}</td>
                    <td class="p-4">{{ $stock->description }}</td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('penjual.stock.edit', $stock->id) }}" class="btn btn-primary py-1 px-3 rounded">Edit</a>
                        <form action="{{ route('penjual.stock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus stock ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger py-1 px-3 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection