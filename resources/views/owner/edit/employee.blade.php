{{-- filepath: /home/acra/bliss/resources/views/owner/edit/employee.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Karyawan/Penjual</h1>
    <form action="{{ route('owner.employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $employee->name) }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', $employee->email) }}">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="penjual" {{ old('role', $employee->role) == 'penjual' ? 'selected' : '' }}>Penjual</option>
                <option value="manajer" {{ old('role', $employee->role) == 'manajer' ? 'selected' : '' }}>Manajer</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (opsional)</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('owner.employees') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection