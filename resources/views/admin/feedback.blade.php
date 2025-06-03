@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Feedback Pelanggan</h1>

    <ul class="space-y-4">
        @foreach ($feedbacks as $fb)
        <li class="border p-4 rounded-md shadow-sm">
            <p class="text-sm text-gray-700">{{ $fb->message }}</p>
            <p class="text-xs text-gray-500 mt-1">— {{ $fb->customer->name }}, {{ $fb->created_at->diffForHumans() }}</p>
        </li>
        @endforeach
    </ul>
</div>
@endsection
