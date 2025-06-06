<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

// Seller Controllers
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\StockController as SellerStockController;
use App\Http\Controllers\Seller\MenuController as SellerMenuController;
use App\Http\Controllers\Seller\FeedbackController as SellerFeedbackController;

// Buyer Controllers
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\CheckoutController;

// Owner Controllers
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\EmployeeController;

// Manager Controllers
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;

// Redirect root
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/test-role', function () {
    return 'Role middleware works!';
})->middleware(['auth', 'role:penjual']);

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// General authenticated route (optional dashboard redirect)
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    return match ($role) {
        'pembeli' => redirect()->route('customer.menu'),
        'penjual' => redirect()->route('penjual.dashboard'),
        'manajer' => redirect()->route('manajer.dashboard'),
        'owner' => redirect()->route('owner.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

// ===========================================
// Role: Penjual
// ===========================================
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/stock', [SellerStockController::class, 'index'])->name('stock.index');
    Route::post('/stock/update', [SellerStockController::class, 'update'])->name('stock.update');

    Route::resource('/menu', SellerMenuController::class)->except(['show']);

    Route::get('/feedback', [SellerFeedbackController::class, 'index'])->name('feedback.index');
});

// ===========================================
// Role: Pembeli
// ===========================================
Route::middleware(['auth', 'role:pembeli'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/test', [MenuController::class, 'index']);
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
});

// ===========================================
// Role: Owner
// ===========================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// ===========================================
// Role: Manajer
// ===========================================
Route::middleware(['auth', 'role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    // Add more manager-specific routes here
});

// ===========================================
// Profile (umum)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
