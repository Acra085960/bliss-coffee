{{-- filepath: /home/acra/bliss/resources/views/owner/menus.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kelola Harga Menu</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td>{{ $menu->name }}</td>
                <td>{{ $menu->category }}</td>
                <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td>
                    @if($menu->is_available)
                        <span class="badge bg-success">Tersedia</span>
                    @else
                        <span class="badge bg-danger">Tidak Tersedia</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('owner.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit Harga
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada menu.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection