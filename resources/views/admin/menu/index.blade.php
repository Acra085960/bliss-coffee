@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header Section with Title and Add Menu Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Daftar Menu</h1>
        <!-- Add Menu Button -->
        <button onclick="window.location='{{ route('penjual.menu.create') }}'" 
            class="btn btn-primary btn-lg rounded-lg mt-6 mb-6">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Menu
        </button>
    </div>

    <!-- Table for Menu List -->
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-4 font-medium text-sm text-gray-700">Nama</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Harga</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Stok</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @foreach ($menus as $menu)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $menu->name }}</td>
                    <td class="p-4">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $menu->stock }}</td>
                    <td class="p-4">
                        <div class="flex items-center justify-start">
                            <a href="{{ route('penjual.menu.edit', $menu->id) }}" class="text-blue-500 hover:text-blue-700 mr-4 transition duration-300">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('penjual.menu.destroy', $menu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 transition duration-300" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
