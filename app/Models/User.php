<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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
        'is_active',
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
    public function menus()
{
    return $this->hasMany(\App\Models\Menu::class, 'user_id');
}

public function orders()
{
    return $this->hasManyThrough(
        \App\Models\Order::class,
        \App\Models\Outlet::class,
        'user_id',    // Foreign key di Outlet
        'outlet_id',  // Foreign key di Order
        'id',         // Local key di User
        'id'          // Local key di Outlet
    );
}
public function feedbacks() {
    return $this->hasMany(\App\Models\Feedback::class, 'user_id');
}

public function outlets()
{
    return $this->hasMany(Outlet::class);
}

public function stocks()
{
    return $this->hasManyThrough(
        \App\Models\Stock::class,
        \App\Models\Outlet::class,
        'user_id',    // Foreign key di Outlet
        'outlet_id',  // Foreign key di Stock
        'id',         // Local key di User
        'id'          // Local key di Outlet
    );
}
}
