@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Manajemen Stok</h1>

    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Menu</th>
                <th class="p-2 text-left">Stok</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
            <tr>
                <td class="p-2">{{ $menu->name }}</td>
                <td class="p-2">{{ $menu->stock }}</td>
                <td class="p-2 text-center">
                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="text-blue-500">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
