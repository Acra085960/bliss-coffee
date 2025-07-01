<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

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
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

// Owner Controllers
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\FeedbackController as OwnerFeedbackController;
use App\Http\Controllers\Owner\OutletController as OwnerOutletController;


// Manager Controllers
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\SalesAnalysisController;
use App\Http\Controllers\Manager\StockController;
use App\Http\Controllers\Manager\SellerPerformanceController;
use App\Http\Controllers\Manager\TopMenusController;
use App\Http\Controllers\Manager\SalesExportController;

// Redirect root
Route::get('/', function () {
    return view('auth.login');
});

// Auth routes (from Breeze)
require __DIR__.'/auth.php';

// ===========================================
// General authenticated route (optional dashboard redirect)
// ===========================================
Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// ===========================================
// Role: Penjual (Seller)
// ===========================================
Route::middleware(['auth', 'role:penjual', 'verified'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/batch-update-status', [SellerOrderController::class, 'batchUpdateStatus'])->name('orders.batchUpdate');
    Route::resource('/stock', SellerStockController::class);
    Route::post('/stock/update-stock', [SellerStockController::class, 'updateStock'])->name('stock.update');
    Route::post('/stock/bulk-update', [SellerStockController::class, 'bulkUpdate'])->name('stock.bulk-update');
    Route::get('/stock/{stock}/movements', [SellerStockController::class, 'movements'])->name('stock.movements');
    Route::get('/low-stock', [SellerStockController::class, 'lowStock'])->name('stock.low-stock');
    Route::resource('/menu', SellerMenuController::class)->except(['show']);
    Route::post('/menu/{menu}/toggle-availability', [SellerMenuController::class, 'toggleAvailability'])->name('menu.toggle-availability');
    Route::get('/feedback', [SellerFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [SellerFeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/feedback/{feedback}/respond', [SellerFeedbackController::class, 'respond'])->name('feedback.respond');
    Route::post('/feedback/{feedback}/update-response', [SellerFeedbackController::class, 'updateResponse'])->name('feedback.update-response');
    Route::get('/feedback-analytics', [SellerFeedbackController::class, 'analytics'])->name('feedback.analytics');
});

// ===========================================
// Role: Pembeli (Buyer)
// ===========================================
Route::middleware(['auth', 'role:pembeli', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/cart/total', [CartController::class, 'getCartTotal'])->name('cart.total');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order-success/{order}', [CheckoutController::class, 'orderSuccess'])->name('order-success');
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/reorder', [CustomerOrderController::class, 'reorder'])->name('orders.reorder');
    Route::get('/track/{orderNumber?}', [CustomerOrderController::class, 'trackOrder'])->name('track-order');
    Route::get('/orders/status', [CustomerOrderController::class, 'getOrderStatus'])->name('orders.status');
    Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::patch('/cart/preference/{key}', [CartController::class, 'updatePreference'])->name('cart.updatePreference');
});

// ===========================================
// Role: Owner
// ===========================================
Route::middleware(['auth', 'role:owner', 'verified'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    // Kelola Pegawai/Manajer
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    // Laporan Penjualan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    // ===== Tambahkan route export di bawah ini =====
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    // ==============================================
    // Kelola Harga Menu
    Route::get('/menus', [OwnerMenuController::class, 'index'])->name('menus');
    Route::get('/menus/{menu}/edit', [OwnerMenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [OwnerMenuController::class, 'update'])->name('menus.update');
    // Feedback Konsumen
    Route::get('/feedback', [OwnerFeedbackController::class, 'index'])->name('feedback');
    // Monitoring Outlet/Gerobak
    Route::get('/outlets', [OwnerOutletController::class, 'index'])->name('outlets');
    Route::patch('/outlets/{outlet}/assign-penjual', [OwnerOutletController::class, 'assignPenjual'])->name('outlets.assignPenjual');
    Route::patch('/outlets/{outlet}/toggle-active', [OwnerOutletController::class, 'toggleActive'])->name('outlets.toggleActive');
});

// ===========================================
// Role: Manajer (Manager)
// ===========================================
Route::middleware(['auth', 'role:manajer', 'verified'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sales/analysis', [SalesAnalysisController::class, 'index'])->name('sales.analysis');
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::post('/stocks/{stock}/update', [StockController::class, 'update'])->name('stocks.update');
        Route::get('/sellers/performance', [SellerPerformanceController::class, 'index'])->name('sellers.performance');
        Route::get('/topmenus', [TopMenusController::class, 'index'])->name('topmenus');
        Route::get('/sales/export', [SalesExportController::class, 'index'])->name('sales.export');
        Route::get('/sales/export/csv', [SalesExportController::class, 'exportCsv'])->name('sales.export.csv');
        Route::get('/sales/export/pdf', [SalesExportController::class, 'exportPdf'])->name('sales.export.pdf');
    });

// ===========================================
// Profile (umum)
// ===========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', function(Request $request) {
        if ($request->has('password_update')) {
            return app(ProfileController::class)->updatePassword($request);
        }
        return app(ProfileController::class)->update($request);
    })->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Midtrans callback (outside auth middleware)
Route::post('/payment/callback', [CheckoutController::class, 'paymentCallback'])->name('payment.callback');