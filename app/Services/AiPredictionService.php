<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AiPredictionService
{
    /**
     * Score each tenant's late-payment risk (0–100).
     * Higher score = higher risk.
     */
    public function latePaymentRisk(): Collection
    {
        return Tenant::with(['invoices', 'activeLease.unit.property'])->get()
            ->filter(fn ($t) => $t->activeLease !== null)
            ->map(function (Tenant $tenant) {
                $invoices     = $tenant->invoices;
                $total        = $invoices->count();
                $late         = $invoices->where('status', 'late')->count();
                $unpaid       = $invoices->whereIn('status', ['pending'])->where('due_date', '<', now())->count();
                $lateRate     = $total > 0 ? ($late + $unpaid) / $total : 0;

                // Days since last payment
                $lastPaid  = $invoices->where('status', 'paid')->sortByDesc('paid_date')->first();
                $daysSince = $lastPaid ? Carbon::parse($lastPaid->paid_date)->diffInDays(now()) : 90;

                $score = min(100, round(($lateRate * 60) + min(40, $daysSince / 3)));

                return [
                    'tenant'      => $tenant,
                    'score'       => $score,
                    'risk_level'  => $score >= 70 ? 'high' : ($score >= 40 ? 'medium' : 'low'),
                    'late_count'  => $late + $unpaid,
                    'total_inv'   => $total,
                ];
            })
            ->sortByDesc('score')
            ->values();
    }

    /**
     * Predict how long available units are likely to stay vacant.
     * Based on average vacancy duration of previously rented units.
     */
    public function vacancyForecast(): Collection
    {
        $available = Unit::where('status', 'available')->with('property')->get();

        return $available->map(function (Unit $unit) {
            $avgDaysToLease = Lease::where('unit_id', $unit->id)
                ->whereNotNull('start_date')
                ->get()
                ->avg(fn ($l) => $l->start_date->diffInDays($l->created_at));

            $avgDaysToLease = $avgDaysToLease ?? 30;

            $vacant_since   = $unit->updated_at;
            $days_vacant    = $vacant_since->diffInDays(now());
            $likelihood     = min(100, round(($days_vacant / max(1, $avgDaysToLease)) * 100));

            return [
                'unit'            => $unit,
                'days_vacant'     => $days_vacant,
                'avg_days_lease'  => round($avgDaysToLease),
                'fill_likelihood' => $likelihood,
                'urgency'         => $days_vacant > $avgDaysToLease * 1.5 ? 'high' : ($days_vacant > $avgDaysToLease * 0.8 ? 'medium' : 'low'),
            ];
        })->sortByDesc('days_vacant')->values();
    }

    /**
     * Predict which units are due for preventive maintenance
     * based on last completed maintenance and average interval.
     */
    public function preventiveMaintenance(): Collection
    {
        $units = Unit::with(['property', 'maintenanceRequests' => fn ($q) =>
            $q->where('status', 'resolved')->latest('completed_at')->take(5)
        ])->get();

        return $units->map(function (Unit $unit) {
            $resolved = $unit->maintenanceRequests->filter(fn ($r) => $r->completed_at !== null);

            if ($resolved->count() < 2) {
                return null;
            }

            $intervals = $resolved->sortByDesc('completed_at')
                ->sliding(2)
                ->map(fn ($pair) => abs($pair->first()->completed_at->diffInDays($pair->last()->completed_at)))
                ->average();

            $lastDone   = $resolved->sortByDesc('completed_at')->first()->completed_at;
            $daysSince  = $lastDone->diffInDays(now());
            $dueScore   = $intervals > 0 ? min(100, round(($daysSince / $intervals) * 100)) : 0;

            return [
                'unit'        => $unit,
                'last_done'   => $lastDone->format('Y/m/d'),
                'avg_interval'=> round($intervals),
                'days_since'  => $daysSince,
                'due_score'   => $dueScore,
                'overdue'     => $dueScore >= 90,
            ];
        })
        ->filter()
        ->sortByDesc('due_score')
        ->values();
    }
}
