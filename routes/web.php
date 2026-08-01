<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaundryOrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\LaundryServiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Public Customer Tracking Portal
Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');

// Public Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
//test
    Route::get('/ping', fn() => 'OK');

    Route::get('/db-test', function () {
        $start = microtime(true);
        DB::select('SELECT 1');
        return microtime(true) - $start;
    });

    Route::get('/php-test', function () {
        $start = microtime(true);

        for ($i = 0; $i < 1000000; $i++) {}

        return microtime(true) - $start;
    });
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customer CRUD
    Route::resource('customers', CustomerController::class);

    // Laundry Orders
    Route::resource('laundry/orders', LaundryOrderController::class)->names([
        'index' => 'laundry.orders.index',
        'create' => 'laundry.orders.create',
        'store' => 'laundry.orders.store',
        'show' => 'laundry.orders.show',
        'edit' => 'laundry.orders.edit',
        'update' => 'laundry.orders.update',
        'destroy' => 'laundry.orders.destroy',
    ]);
    Route::patch('laundry/orders/{order}/status', [LaundryOrderController::class, 'updateStatus'])->name('laundry.orders.updateStatus');
    Route::patch('laundry/orders/{order}/payment', [LaundryOrderController::class, 'updatePayment'])->name('laundry.orders.updatePayment');

    // Deliveries
    Route::get('deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::patch('deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.updateStatus');

    // Routes restricted to Admin or Owner
    Route::middleware(['role:admin,owner'])->group(function () {
        // Laundry Services Master
        Route::resource('services', LaundryServiceController::class);

        // Boarding House (Rooms & Tenants)
        Route::resource('rooms', RoomController::class);
        Route::resource('tenants', TenantController::class);
        Route::get('tenants/{tenant}/renew', [TenantController::class, 'showRenewForm'])->name('tenants.renew.form');
        Route::post('tenants/{tenant}/renew', [TenantController::class, 'renew'])->name('tenants.renew');
        Route::post('tenants/payments/{payment}/pay', [TenantController::class, 'payPayment'])->name('tenants.payments.pay');

        // Finance
        Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::post('finance', [FinanceController::class, 'store'])->name('finance.store');
        Route::delete('finance/{transaction}', [FinanceController::class, 'destroy'])->name('finance.destroy');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    // Routes restricted to Admin only
    Route::middleware(['role:admin'])->group(function () {
        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
    
    // Profile Controller
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
