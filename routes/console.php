<?php

use App\Console\Commands\GenerateMonthlyInvoices;
use App\Console\Commands\NotifyLeaseExpiry;
use App\Console\Commands\SendRentReminders;
use App\Console\Commands\UpdateAiPredictions;
use App\Console\Commands\UpdateLateInvoices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled tasks ───────────────────────────────────────────────────────────

// Generate monthly rent invoices on the 1st of each month at 00:01
Schedule::command(GenerateMonthlyInvoices::class)->monthlyOn(1, '00:01');

// Mark overdue invoices as late — runs daily at 01:00
Schedule::command(UpdateLateInvoices::class)->dailyAt('01:00');

// Send lease expiry notifications — runs daily at 08:00
Schedule::command(NotifyLeaseExpiry::class)->dailyAt('08:00');

// Send rent reminders — runs daily at 08:30
Schedule::command(SendRentReminders::class)->dailyAt('08:30');

// Refresh AI predictions — runs daily at 02:00
Schedule::command(UpdateAiPredictions::class)->dailyAt('02:00');
