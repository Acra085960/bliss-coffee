{{-- filepath: /home/acra/bliss/resources/views/owner/employees.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Karyawan/Penjual</h1>
    <a href="{{ route('owner.employees.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Karyawan/Penjual
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ ucfirst($employee->role) }}</td>
                <td>
                    <a href="{{ route('owner.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('owner.employees.destroy', $employee->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection{{-- filepath: /home/acra/bliss/resources/views/owner/employees.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Karyawan/Penjual</h1>
    <a href="{{ route('owner.employees.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Karyawan/Penjual
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ ucfirst($employee->role) }}</td>
                <td>
                    <a href="{{ route('owner.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('owner.employees.destroy', $employee->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection