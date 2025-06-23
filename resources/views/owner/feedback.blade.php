{{-- filepath: /home/acra/bliss/resources/views/owner/feedback.blade.php --}}
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
    <h1>Feedback Konsumen</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Penjual</th>
                <th>Pesanan</th>
                <th>Rating</th>
                <th>Komentar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
            <tr>
                <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $feedback->customer->name ?? '-' }}</td>
                <td>{{ $feedback->user->name ?? '-' }}</td>
                <td>#{{ $feedback->order_id ?? '-' }}</td>
                <td>
                    @if($feedback->rating)
                        <span class="badge bg-success">{{ $feedback->rating }}/5</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </td>
                <td>{{ $feedback->comment }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada feedback.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection