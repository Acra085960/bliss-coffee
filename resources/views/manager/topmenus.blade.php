{{-- filepath: /home/acra/bliss/resources/views/manager/topmenus.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .container, .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .card, .card.mb-4 {
            margin-bottom: 1rem;
        }
        .card-body, .card-header {
            padding: 1rem;
        }
        .table-responsive, .table {
            font-size: 0.95rem;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        h1, .card-title, .card-header, .font-bold {
            font-size: 1.1rem;
        }
    }
    /* Agar tabel bisa discroll di layar kecil */
    .card-body.p-0 {
        overflow-x: auto;
    }
</style>
@endpush\

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Menu Terlaris</h1>

    <div class="card">
        <div class="card-header font-bold">Daftar Menu Terlaris</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Penjual</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topMenus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->seller->name ?? '-' }}</td>
                            <td>{{ $menu->sold_count ?? 0 }}</td>
                            <td>Rp{{ number_format($menu->total_income ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data menu terlaris.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection