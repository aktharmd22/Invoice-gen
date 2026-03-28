<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Billing
    Route::get('/billing/create',          [BillingController::class, 'create'])->name('billing.create');
    Route::post('/billing',                [BillingController::class, 'store'])->name('billing.store');
    Route::get('/billing',                 [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/{billing}',       [BillingController::class, 'show'])->name('billing.show');
    Route::get('/billing/{billing}/edit',  [BillingController::class, 'edit'])->name('billing.edit');
    Route::put('/billing/{billing}',       [BillingController::class, 'update'])->name('billing.update');
    Route::delete('/billing/{billing}',    [BillingController::class, 'destroy'])->name('billing.destroy');
    Route::get('/billing/{billing}/pdf',   [BillingController::class, 'downloadPdf'])->name('billing.pdf');

    // Returns
    Route::get('/returns/create',          [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns',                [ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns',                 [ReturnController::class, 'index'])->name('returns.index');
    Route::delete('/returns/{return}',     [ReturnController::class, 'destroy'])->name('returns.destroy');

    // Expenses
    Route::get('/expenses/create',         [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses',               [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses',                [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}',      [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}',   [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    // Accounts
    Route::get('/accounts/ledger',         [AccountController::class, 'ledger'])->name('accounts.ledger');
    Route::get('/accounts/profit-loss',    [AccountController::class, 'profitLoss'])->name('accounts.profit-loss');

    // Settings
    Route::get('/settings',               [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings',              [SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/password',      [SettingController::class, 'updatePassword'])->name('settings.password');
});
