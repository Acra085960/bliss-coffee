{{-- filepath: /home/acra/bliss/resources/views/manager/performance.blade.php --}}
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
@endpush

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Monitoring Kinerja Penjual</h1>

    <div class="card">
        <div class="card-header font-bold">Daftar Penjual & Kinerjanya</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Nama Penjual</th>
                        <th>Email</th>
                        <th>Jumlah Pesanan</th>
                        <th>Total Penjualan</th>
                        <th>Rata-rata Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $seller)
                        <tr>
                            <td>{{ $seller->name }}</td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->orders_count ?? 0 }}</td>
                            <td>Rp{{ number_format($seller->total_sales ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if(isset($seller->average_rating))
                                    {{ number_format($seller->average_rating, 2) }} / 5
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data penjual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection