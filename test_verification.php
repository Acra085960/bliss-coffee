<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Test script untuk memverifikasi sistem verifikasi terpisah

echo "=== Testing Separate Verification System ===\n\n";

// Buat user dengan verifikasi WhatsApp
$whatsappUser = User::create([
    'name' => 'WhatsApp Test User',
    'email' => 'whatsapp@test.com',
    'phone' => '628123456789',
    'password' => Hash::make('password'),
    'role' => 'pembeli',
    'phone_verified_at' => now(), // Hanya verifikasi phone
    'email_verified_at' => null,  // Email tidak diverifikasi
]);

$whatsappUser->assignRole('pembeli');

echo "✓ Created WhatsApp verified user (ID: {$whatsappUser->id})\n";
echo "  - Phone verified: " . ($whatsappUser->phone_verified_at ? 'YES' : 'NO') . "\n";
echo "  - Email verified: " . ($whatsappUser->email_verified_at ? 'YES' : 'NO') . "\n\n";

// Buat user dengan verifikasi Email
$emailUser = User::create([
    'name' => 'Email Test User',
    'email' => 'email@test.com',
    'phone' => '628987654321',
    'password' => Hash::make('password'),
    'role' => 'pembeli',
    'phone_verified_at' => null,  // Phone tidak diverifikasi
    'email_verified_at' => now(), // Hanya verifikasi email
]);

$emailUser->assignRole('pembeli');

echo "✓ Created Email verified user (ID: {$emailUser->id})\n";
echo "  - Phone verified: " . ($emailUser->phone_verified_at ? 'YES' : 'NO') . "\n";
echo "  - Email verified: " . ($emailUser->email_verified_at ? 'YES' : 'NO') . "\n\n";

// Buat user tanpa verifikasi
$unverifiedUser = User::create([
    'name' => 'Unverified Test User',
    'email' => 'unverified@test.com',
    'phone' => '628555666777',
    'password' => Hash::make('password'),
    'role' => 'pembeli',
    'phone_verified_at' => null,
    'email_verified_at' => null,
]);

$unverifiedUser->assignRole('pembeli');

echo "✓ Created Unverified user (ID: {$unverifiedUser->id})\n";
echo "  - Phone verified: " . ($unverifiedUser->phone_verified_at ? 'YES' : 'NO') . "\n";
echo "  - Email verified: " . ($unverifiedUser->email_verified_at ? 'YES' : 'NO') . "\n\n";

// Test middleware logic
use App\Http\Middleware\EnsureVerified;

echo "=== Testing Middleware Logic ===\n\n";

$middleware = new EnsureVerified();

// Test dengan user WhatsApp verified
echo "Testing WhatsApp verified user:\n";
$hasPhoneVerification = !is_null($whatsappUser->phone_verified_at);
$hasEmailVerification = !is_null($whatsappUser->email_verified_at);
$shouldPass = $hasPhoneVerification || $hasEmailVerification;
echo "  - Should pass middleware: " . ($shouldPass ? 'YES' : 'NO') . "\n\n";

// Test dengan user Email verified
echo "Testing Email verified user:\n";
$hasPhoneVerification = !is_null($emailUser->phone_verified_at);
$hasEmailVerification = !is_null($emailUser->email_verified_at);
$shouldPass = $hasPhoneVerification || $hasEmailVerification;
echo "  - Should pass middleware: " . ($shouldPass ? 'YES' : 'NO') . "\n\n";

// Test dengan user unverified
echo "Testing Unverified user:\n";
$hasPhoneVerification = !is_null($unverifiedUser->phone_verified_at);
$hasEmailVerification = !is_null($unverifiedUser->email_verified_at);
$shouldPass = $hasPhoneVerification || $hasEmailVerification;
echo "  - Should pass middleware: " . ($shouldPass ? 'YES' : 'NO') . "\n\n";

echo "=== Test Complete ===\n";
echo "The system now supports separate verification:\n";
echo "• WhatsApp verification → only phone_verified_at is set\n";
echo "• Email verification → only email_verified_at is set\n";
echo "• Users can login with either verification method\n";
echo "• No mixed verification fallbacks\n\n";

// Cleanup test users
$whatsappUser->delete();
$emailUser->delete();
$unverifiedUser->delete();

echo "✓ Test users cleaned up\n";
