<?php

namespace App\Observers;

use App\Models\Lease;

class LeaseObserver
{
    public function created(Lease $lease): void
    {
        $lease->unit()->update(['status' => 'occupied']);
    }

    public function updated(Lease $lease): void
    {
        if (! $lease->wasChanged('status')) { return; }

        $unitStatus = match ($lease->status) {
            'terminated', 'expired' => 'available',
            'active'                => 'occupied',
            'pending'               => 'reserved',
            default                 => null,
        };

        if ($unitStatus !== null) {
            $lease->unit()->update(['status' => $unitStatus]);
        }
    }

    public function deleted(Lease $lease): void
    {
        $hasActiveLease = $lease->unit->leases()
            ->where('id', '!=', $lease->id)
            ->whereIn('status', ['active', 'pending'])
            ->exists();

        if (! $hasActiveLease) {
            $lease->unit()->update(['status' => 'available']);
        }
    }
}