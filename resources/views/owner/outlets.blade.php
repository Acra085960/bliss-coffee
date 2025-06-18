{{-- filepath: /home/acra/bliss/resources/views/owner/outlets.blade.php --}}
@extends('layouts.app')

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