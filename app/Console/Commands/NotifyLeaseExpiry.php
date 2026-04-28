<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Notifications\LeaseExpiryNotification;
use Illuminate\Console\Command;

class NotifyLeaseExpiry extends Command
{
    protected $signature   = 'aqari:leases:expiry-notify';
    protected $description = 'Send notifications for leases expiring soon';

    public function handle(): int
    {
        if (!config('aqari.lease_expiry_notify', true)) {
            return 0;
        }

        $days = config('aqari.lease_expiry_days', 30);

        $leases = Lease::where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->with(['tenant', 'unit.property'])
            ->get();

        foreach ($leases as $lease) {
            LeaseExpiryNotification::send($lease);
        }

        $this->info("Sent expiry notifications for {$leases->count()} lease(s).");
        return 0;
    }
}
