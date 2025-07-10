<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PhoneVerificationCode extends Model
{
    use HasFactory;

    protected $table = 'phone_verification_codes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'used',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Scope untuk kode yang belum expired
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope untuk kode yang belum diverifikasi
     */
    public function scopeNotUsed($query)
    {
        return $query->where('used', false);
    }

    /**
     * Check apakah kode masih valid
     */
    public function isValid(): bool
    {
        return $this->expires_at > now() && !$this->used;
    }

    /**
     * Mark kode sebagai sudah digunakan
     */
    public function markAsUsed()
    {
        $this->update(['used' => true]);
    }

    /**
     * Generate kode verifikasi 6 digit
     */
    public static function generateCode(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Buat kode verifikasi baru untuk nomor telepon
     */
    public static function createForPhone(string $phone): self
    {
        // Hapus kode lama yang belum diverifikasi untuk nomor ini
        static::where('phone', $phone)
            ->where('used', false)
            ->delete();

        return static::create([
            'phone' => $phone,
            'code' => static::generateCode(),
            'expires_at' => now()->addMinutes(10), // Expired dalam 10 menit
            'used' => false,
        ]);
    }

    /**
     * Verifikasi kode untuk nomor telepon
     */
    public static function verifyCode(string $phone, string $code): bool
    {
        $verification = static::where('phone', $phone)
            ->where('code', $code)
            ->notExpired()
            ->notUsed()
            ->first();

        if ($verification) {
            $verification->markAsUsed();
            return true;
        }

        return false;
    }
}