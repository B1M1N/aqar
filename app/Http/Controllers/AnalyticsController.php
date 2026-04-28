<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $this->authorize('analytics.view');

        // Occupancy by property
        $properties = Property::withCount([
            'units',
            'units as occupied_count' => fn ($q) => $q->where('status', 'occupied'),
        ])->get();

        // Revenue last 12 months
        $months   = collect();
        $revenues = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date    = Carbon::now()->subMonths($i);
            $months->push($date->locale('ar')->isoFormat('MMM YY'));
            $revenues->push(
                Invoice::where('status', 'paid')
                    ->whereYear('paid_date', $date->year)
                    ->whereMonth('paid_date', $date->month)
                    ->sum('amount')
            );
        }

        // Maintenance breakdown by status
        $maintenanceStats = MaintenanceRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Maintenance breakdown by type
        $maintenanceByType = MaintenanceRequest::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        // Active leases expiring soon (next 30 days)
        $expiringLeases = Lease::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->with(['unit.property', 'tenant'])
            ->get();

        // Late invoices
        $lateInvoices = Invoice::where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->where('due_date', '<', now())
            ->with(['tenant', 'unit'])
            ->latest('due_date')
            ->take(10)
            ->get();

        // Unit status distribution
        $unitStats = Unit::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('analytics.index', compact(
            'properties', 'months', 'revenues', 'maintenanceStats',
            'maintenanceByType', 'expiringLeases', 'lateInvoices', 'unitStats'
        ));
    }
}
