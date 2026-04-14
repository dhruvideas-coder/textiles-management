<?php

use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\AnalyticsController as OwnerAnalyticsController;
use App\Http\Controllers\Owner\BillController;
use App\Http\Controllers\Owner\ChallanController;
use App\Http\Controllers\Owner\CustomerController;
use App\Http\Controllers\Owner\InventoryController;
use App\Http\Controllers\Owner\ShopSettingController;
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\Owner\SubscriptionController as OwnerSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware('auth')->group(function (): void {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')
        ->as('admin.')
        ->middleware('role:super_admin')
        ->group(function (): void {
            Route::resource('shops', ShopController::class);
            Route::resource('users', UserController::class);
            Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::put('subscriptions/{shop}', [AdminSubscriptionController::class, 'update'])->name('subscriptions.update');
            Route::get('analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
            Route::post('impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
            Route::post('impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
        });

    Route::prefix('owner')
        ->as('owner.')
        ->middleware('tenant.role:owner|staff')
        ->group(function (): void {
            Route::resource('bills', BillController::class);
            Route::post('bills/{bill}/duplicate', [BillController::class, 'duplicate'])->name('bills.duplicate');
            Route::get('bills/{bill}/pdf', [BillController::class, 'downloadPdf'])->name('bills.pdf');
            Route::get('bills/{bill}/thermal', [BillController::class, 'printThermal'])->name('bills.thermal');

            Route::resource('challans', ChallanController::class);
            Route::post('challans/{challan}/duplicate', [ChallanController::class, 'duplicate'])->name('challans.duplicate');
            Route::get('challans/{challan}/pdf', [ChallanController::class, 'downloadPdf'])->name('challans.pdf');

            Route::resource('customers', CustomerController::class);
            Route::resource('inventory', InventoryController::class);
            Route::get('analytics', [OwnerAnalyticsController::class, 'index'])->name('analytics.index');
        });

    Route::prefix('owner')
        ->as('owner.')
        ->middleware('tenant.role:owner')
        ->group(function (): void {
            Route::resource('staff', StaffController::class);
            Route::get('settings', [ShopSettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [ShopSettingController::class, 'update'])->name('settings.update');
            Route::get('subscription', [OwnerSubscriptionController::class, 'show'])->name('subscription.show');
        });
});
