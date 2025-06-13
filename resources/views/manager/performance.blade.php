{{-- filepath: /home/acra/bliss/resources/views/manager/performance.blade.php --}}
@extends('layouts.app')

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