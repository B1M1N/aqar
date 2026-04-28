<?php

namespace App\Services;

use App\Models\Lease;
use App\Models\LeaseRenewal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LeaseService
{
    public function terminate(Lease $lease, string $reason): void
    {
        $lease->update(['status' => 'terminated', 'notes' => $lease->notes . "\n[إنهاء العقد] " . $reason]);
    }

    public function renew(Lease $lease, array $data): LeaseRenewal
    {
        $renewal = $lease->renewals()->create([
            'old_end_date'    => $lease->end_date,
            'new_end_date'    => $data['new_end_date'],
            'new_rent_amount' => $data['new_rent_amount'],
            'renewed_by'      => auth()->id(),
        ]);

        $lease->update([
            'end_date'    => $data['new_end_date'],
            'rent_amount' => $data['new_rent_amount'],
            'status'      => 'active',
        ]);

        return $renewal;
    }

    public function generatePdf(Lease $lease): string
    {
        $lease->load(['unit.property', 'tenant']);

        $pdf  = Pdf::loadView('pdf.lease', compact('lease'))
                   ->setPaper('a4', 'portrait');
        $path = 'leases/contract-' . $lease->id . '.pdf';

        Storage::disk('public')->put($path, $pdf->output());

        $lease->update(['contract_pdf' => $path]);

        return $path;
    }
}
