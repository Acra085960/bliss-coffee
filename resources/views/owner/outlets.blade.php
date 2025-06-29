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
    .table-responsive, .container .table {
        overflow-x: auto;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1>Monitoring Outlet/Gerobak</h1>
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Outlet</th>
                <th>Penanggung Jawab</th>
                <th>Email Penjual</th>
                <th>Email Outlet</th>
                <th>Omzet</th>
                <th>Jumlah Pesanan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outlets as $outlet)
            <tr>
                <td>{{ $outlet->name }}</td>
                <td>{{ $outlet->user ? $outlet->user->name : 'Tidak ada penjual' }}</td>
                <td>{{ $outlet->user ? $outlet->user->email : '-' }}</td>
                <td>{{ $outlet->email ?? '-' }}</td>
                <td>Rp {{ number_format($outlet->orders->sum('total_price'), 0, ',', '.') }}</td>
                <td>{{ $outlet->orders->count() }}</td>
                <td>
                    @if($outlet->is_active ?? true)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignPenjualModal{{ $outlet->id }}">
                        <i class="fas fa-user-edit"></i> Assign Penjual
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada outlet/gerobak.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Modal Assign Penjual (letakkan di luar table agar modal Bootstrap berfungsi) --}}
    @foreach($outlets as $outlet)
    <div class="modal fade" id="assignPenjualModal{{ $outlet->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('owner.outlets.assignPenjual', $outlet->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Assign Penjual ke {{ $outlet->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="user_id_{{ $outlet->id }}" class="form-label">Pilih Penjual</label>
                    <select name="user_id" id="user_id_{{ $outlet->id }}" class="form-select">
                        <option value="">-- Tidak ada penjual --</option>
                        @foreach($penjuals as $penjual)
                            <option value="{{ $penjual->id }}" {{ $outlet->user_id == $penjual->id ? 'selected' : '' }}>
                                {{ $penjual->name }} ({{ $penjual->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection