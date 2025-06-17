<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Company setup route (for new users without company)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/setup/company', [CompanyController::class, 'create'])->name('setup.company');
    Route::post('/setup/company', [CompanyController::class, 'store'])->name('setup.company.store');
});

// Protected routes that require tenant (company) access
Route::middleware(['auth', 'verified', \App\Http\Middleware\TenantMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Company management
    Route::get('/company/profile', [CompanyController::class, 'profile'])->name('company.profile');
    Route::get('/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::patch('/company', [CompanyController::class, 'update'])->name('company.update');
    Route::get('/company/opening-balance', [CompanyController::class, 'openingBalance'])->name('company.opening-balance');
    Route::post('/company/opening-balance', [CompanyController::class, 'storeOpeningBalance'])->name('company.opening-balance.store');

    // Transaction management
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

    // User management (admin only - enforced in controller)
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Analytics routes
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/valuation', [DashboardController::class, 'valuation'])->name('valuation');
});

// User profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Subscription management routes (placeholder for future implementation)
Route::middleware('auth')->group(function () {
    Route::get('/subscription/suspended', function () {
        return view('subscription.suspended');
    })->name('subscription.suspended');

    Route::get('/subscription/expired', function () {
        return view('subscription.expired');
    })->name('subscription.expired');
});

require __DIR__.'/auth.php';
