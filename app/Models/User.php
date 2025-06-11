<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** 
     * Menggunakan trait HasFactory dan Notifiable serta HasRoles untuk manajemen role.
     */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang bisa di-assign secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Menambahkan 'role' ke dalam fillable
    ];

    /**
     * Atribut yang perlu disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang akan di-cast ke tipe data lain.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Menentukan casting untuk email_verified_at
            'password' => 'hashed',           // Menandakan password harus di-hash
        ];
    }
}
