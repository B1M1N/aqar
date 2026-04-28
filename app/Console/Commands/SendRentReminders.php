<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\RentReminderNotification;
use Illuminate\Console\Command;

class SendRentReminders extends Command
{
    protected $signature   = 'aqari:invoices:reminders';
    protected $description = 'Send rent payment reminders for upcoming invoices';

    public function handle(): int
    {
        if (!config('aqari.rent_reminders', true)) {
            return 0;
        }

        $days = config('aqari.rent_reminder_days', 5);

        $invoices = Invoice::whereIn('status', ['pending'])
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->with('tenant')
            ->get();

        foreach ($invoices as $invoice) {
            RentReminderNotification::send($invoice);
        }

        $this->info("Sent {$invoices->count()} reminder(s).");
        return 0;
    }
}
