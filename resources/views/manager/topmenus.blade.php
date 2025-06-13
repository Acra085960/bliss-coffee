{{-- filepath: /home/acra/bliss/resources/views/manager/topmenus.blade.php --}}
@extends('layouts.app')

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