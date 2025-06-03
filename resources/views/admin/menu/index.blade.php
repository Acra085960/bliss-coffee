@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Daftar Menu</h1>
        <a href="{{ route('admin.menu.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Menu</a>
    </div>

    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Harga</th>
                <th class="p-2 text-left">Stok</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
            <tr>
                <td class="p-2">{{ $menu->name }}</td>
                <td class="p-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td class="p-2">{{ $menu->stock }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="text-blue-500 mr-2">Edit</a>
                    <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
