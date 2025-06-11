{{-- filepath: /home/acra/bliss/resources/views/admin/orders.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Title and Add Order Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Daftar Pesanan</h1>
        <a href="{{ route('penjual.orders.create') }}" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-300 inline-block">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Pesanan
        </a>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-4 font-medium text-sm text-gray-700">ID</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Pelanggan</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Total Harga</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Status Pesanan</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Status Pembayaran</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Tanggal</th>
                    <th class="p-4 font-medium text-sm text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @foreach ($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $order->id }}</td>
                    <td class="p-4">
                        {{ $order->user->name ?? 'N/A' }}
                    </td>
                    <td class="p-4">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            @if ($order->status == 'pending') bg-yellow-500 text-white
                            @elseif ($order->status == 'completed') bg-green-500 text-white
                            @else bg-red-500 text-white
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            @if ($order->payment_status == 'pending') bg-yellow-400 text-white
                            @elseif ($order->payment_status == 'paid') bg-green-600 text-white
                            @else bg-red-600 text-white
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="p-4">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td class="p-4">
                        <div class="flex items-center justify-start">
                            <a href="{{ route('penjual.orders.edit', $order->id) }}" 
                                class="text-blue-500 hover:text-blue-700 mr-4 transition duration-300">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('penjual.orders.destroy', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 transition duration-300" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection