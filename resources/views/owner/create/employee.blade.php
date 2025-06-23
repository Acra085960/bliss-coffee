{{-- filepath: /home/acra/bliss/resources/views/owner/create/employee.blade.php --}}
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
        .form-label {
            font-size: 1rem;
        }
        .form-control, .form-select {
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
        }
        .btn, .btn-primary, .btn-secondary {
            font-size: 1rem;
            padding: 0.7rem 1rem;
            width: 100%;
            margin-bottom: 0.7rem;
        }
        .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1>Tambah Karyawan/Penjual</h1>
    <form action="{{ route('owner.employees.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Penjual</option>
                <option value="manajer" {{ old('role') == 'manajer' ? 'selected' : '' }}>Manajer</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password Sementara</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('owner.employees') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection