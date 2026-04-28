<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AiPredictionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

// ── Guest-only (auth) routes ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login',                        [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login',                       [LoginController::class, 'login']);
    Route::get('forgot-password',              [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password',             [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}',       [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password',              [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ── Authenticated routes ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications (all authenticated users)
    Route::get('notifications',          [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all',[NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');

    // ── Admin + Manager ───────────────────────────────────────────────────
    Route::middleware('role:admin|manager')->group(function () {

        // Properties
        Route::resource('properties', PropertyController::class);

        // Units
        Route::resource('units', UnitController::class);

        // Tenants
        Route::resource('tenants', TenantController::class);

        // Leases
        Route::resource('leases', LeaseController::class);
        Route::post('leases/{lease}/renew',    [LeaseController::class, 'renew'])->name('leases.renew');
        Route::post('leases/{lease}/terminate',[LeaseController::class, 'terminate'])->name('leases.terminate');
        Route::get('leases/{lease}/pdf',       [LeaseController::class, 'generatePdf'])->name('leases.pdf');

        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::get('invoices/{invoice}/pdf',  [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');

        // Payments
        Route::post('payments',              [PaymentController::class, 'store'])->name('payments.store');
        Route::post('payments/webhook',      [PaymentController::class, 'moyasarWebhook'])->name('payments.webhook');

        // Analytics & AI (admin + manager)
        Route::get('analytics',   [AnalyticsController::class,   'index'])->name('analytics.index');
        Route::get('ai',          [AiPredictionController::class, 'index'])->name('ai.index');
    });

    // ── Admin + Manager + Staff ───────────────────────────────────────────
    Route::middleware('role:admin|manager|staff')->group(function () {

        // Maintenance
        Route::resource('maintenance', MaintenanceController::class);
        Route::post('maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenance.updateStatus');
        Route::post('maintenance/{maintenance}/assign', [MaintenanceController::class, 'assign'])->name('maintenance.assign');
    });

    // ── Admin only ────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('settings',  [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
