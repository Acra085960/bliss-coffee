{{-- filepath: /home/acra/bliss/resources/views/owner/menus.blade.php --}}
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
        .btn, .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        .table-responsive, .table {
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
    }
    /* Agar tabel bisa discroll di layar kecil */
    .table-responsive, .container .table {
        overflow-x: auto;
        display: block;
    }
</style>
@endpush

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