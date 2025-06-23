{{-- filepath: /home/acra/bliss/resources/views/owner/outlets.blade.php --}}
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
    <h1>Monitoring Outlet/Gerobak</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Penjual</th>
                <th>Email</th>
                <th>Omzet</th>
                <th>Jumlah Pesanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outlets as $outlet)
            <tr>
                <td>{{ $outlet->name }}</td>
                <td>{{ $outlet->email }}</td>
                <td>Rp {{ number_format($outlet->orders->sum('total_price'), 0, ',', '.') }}</td>
                <td>{{ $outlet->orders->count() }}</td>
                <td>
                    @if($outlet->is_active ?? true)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada outlet/gerobak.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection