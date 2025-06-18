<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Jika tabelnya bernama 'employees', tidak perlu $table
    // protected $table = 'employees';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'name',
        'email',
        'role',
        'is_active',
        // tambahkan kolom lain sesuai kebutuhan
    ];

    // Scope untuk pegawai aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Contoh relasi ke User (jika ada)
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}