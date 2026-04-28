<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Lease;
use App\Notifications\RentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateMonthlyInvoices extends Command
{
    protected $signature   = 'aqari:invoices:generate';
    protected $description = 'Generate monthly rent invoices for all active leases';

    public function handle(): int
    {
        if (!config('aqari.auto_invoices', true)) {
            $this->info('Auto invoices disabled — skipping.');
            return 0;
        }

        $month   = now()->month;
        $year    = now()->year;
        $created = 0;

        Lease::where('status', 'active')
            ->with(['tenant', 'unit'])
            ->chunkById(100, function ($leases) use ($month, $year, &$created) {
                foreach ($leases as $lease) {
                    $exists = Invoice::where('lease_id', $lease->id)
                        ->whereYear('due_date', $year)
                        ->whereMonth('due_date', $month)
                        ->where('type', 'rent')
                        ->exists();

                    if ($exists) continue;

                    $due = now()->setDay(min($lease->payment_day, now()->daysInMonth));

                    $invoice = Invoice::create([
                        'lease_id'  => $lease->id,
                        'tenant_id' => $lease->tenant_id,
                        'unit_id'   => $lease->unit_id,
                        'amount'    => $lease->rent_amount,
                        'due_date'  => $due,
                        'type'      => 'rent',
                        'status'    => 'pending',
                    ]);

                    $created++;
                }
            });

        $this->info("Created {$created} invoice(s).");
        return 0;
    }
}
