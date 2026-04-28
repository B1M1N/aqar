<?php

namespace App\Observers;

use App\Models\Lease;

class LeaseObserver
{
    public function created(Lease $lease): void
    {
        // When a new active lease is created, mark the unit as occupied.
        if ($lease->status === 'active') {
            $lease->unit()->update(['status' => 'occupied']);
        }
    }

    public function updated(Lease $lease): void
    {
        // When a lease is terminated or expired, free the unit back to available.
        if ($lease->wasChanged('status') &&
            in_array($lease->status, ['terminated', 'expired'])) {
            $lease->unit()->update(['status' => 'available']);
        }

        // When a pending lease becomes active, occupy the unit.
        if ($lease->wasChanged('status') && $lease->status === 'active') {
            $lease->unit()->update(['status' => 'occupied']);
        }
    }

    public function deleted(Lease $lease): void
    {
        // Soft-deleting an active lease frees the unit.
        if ($lease->status === 'active') {
            $lease->unit()->update(['status' => 'available']);
        }
    }

    public function restored(Lease $lease): void
    {
        if ($lease->status === 'active') {
            $lease->unit()->update(['status' => 'occupied']);
        }
    }

    public function forceDeleted(Lease $lease): void
    {
        //
    }
}
