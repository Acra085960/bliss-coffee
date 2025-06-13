{{-- filepath: resources/views/manager/stocks.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">Stok Per Gerobak/Penjual</h1>

    @forelse($sellers as $seller)
        <div class="card mb-4">
            <div class="card-header font-bold">
                Gerobak: {{ $seller->name }} ({{ $seller->email }})
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Nama Menu/Bahan</th>
                            <th>Sisa Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seller->menus as $menu)
                            <tr>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->stock }}</td>
                                <td>
                                    @if($menu->stock < 10)
                                        <span class="badge badge-danger">Hampir Habis</span>
                                    @else
                                        <span class="badge badge-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada menu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada data penjual.</div>
    @endforelse
</div>
@endsection