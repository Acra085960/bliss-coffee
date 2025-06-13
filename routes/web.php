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
use App\Http\Controllers\Manager\SalesAnalysisController;
use App\Http\Controllers\Manager\StockController;
use App\Http\Controllers\Manager\SellerPerformanceController;
use App\Http\Controllers\Manager\TopMenusController;
use App\Http\Controllers\Manager\SalesExportController;

// Middleware
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CustomAuthMiddleware;

// Redirect root to login
Route::get('/', function () {
    return view('auth.login');
});

// Auth routes (from Breeze)
require __DIR__.'/auth.php';

// Dashboard redirect after login
Route::middleware([CustomAuthMiddleware::class])->get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'pembeli' => redirect()->route('customer.menu'),
        'penjual' => redirect()->route('penjual.dashboard'),
        'manajer' => redirect()->route('manajer.dashboard'),
        'owner'   => redirect()->route('owner.dashboard'),
        default   => abort(403),
    };
})->name('dashboard');

// ===========================================
// Role: Penjual
// ===========================================
Route::middleware([CustomAuthMiddleware::class, RoleMiddleware::class . ':penjual', 'verified'])
    ->prefix('penjual')
    ->name('penjual.')
    ->group(function () {
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('menu', SellerMenuController::class);
        Route::resource('orders', SellerOrderController::class);
        Route::resource('stock', SellerStockController::class);
        Route::resource('feedback', SellerFeedbackController::class);
    });

// ===========================================
// Role: Pembeli
// ===========================================
Route::middleware([CustomAuthMiddleware::class, RoleMiddleware::class . ':pembeli', 'verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
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
Route::middleware([CustomAuthMiddleware::class, RoleMiddleware::class . ':owner', 'verified'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    });

// ===========================================
// Role: Manajer
// ===========================================
Route::middleware([CustomAuthMiddleware::class, RoleMiddleware::class . ':manajer', 'verified'])
    ->prefix('manajer')
    ->name('manajer.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sales/analysis', [SalesAnalysisController::class, 'index'])->name('sales.analysis');
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::get('/sellers/performance', [SellerPerformanceController::class, 'index'])->name('sellers.performance');
        Route::get('/topmenus', [TopMenusController::class, 'index'])->name('topmenus');
        Route::get('/sales/export', [SalesExportController::class, 'index'])->name('sales.export');
                Route::get('/sales/export/csv', [SalesExportController::class, 'exportCsv'])->name('sales.export.csv');
        Route::get('/sales/export/pdf', [SalesExportController::class, 'exportPdf'])->name('sales.export.pdf');
    });

// ===========================================
// Profile (Umum untuk semua user yang login)
Route::middleware([CustomAuthMiddleware::class, 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});