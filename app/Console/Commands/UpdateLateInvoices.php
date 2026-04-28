<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class UpdateLateInvoices extends Command
{
    protected $signature   = 'aqari:invoices:late';
    protected $description = 'Mark overdue unpaid invoices as late';

    public function handle(): int
    {
        if (!config('aqari.late_invoice_updates', true)) {
            return 0;
        }

        $updated = Invoice::where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'late']);

        $this->info("Marked {$updated} invoice(s) as late.");
        return 0;
    }
}
