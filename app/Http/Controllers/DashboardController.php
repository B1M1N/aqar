<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()->hasRole('user')) {
            return redirect()->route('properties.index');
        }

        $totalProperties  = Property::count();
        $totalUnits       = Unit::count();
        $occupiedUnits    = Unit::occupied()->count();
        $occupancyRate    = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;

        $monthlyRevenue   = Invoice::where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('amount');

        $lateInvoices     = Invoice::where('status', 'late')->count();
        $activeLeases     = Lease::where('status', 'active')->count();

        $latestInvoices   = Invoice::with(['tenant', 'unit.property'])
            ->latest()
            ->take(8)
            ->get();

        $topProperties    = Property::withCount(['units as occupied_count' => function ($q) {
                $q->where('status', 'occupied');
            }])
            ->withCount('units as total_count')
            ->having('total_count', '>', 0)
            ->orderByDesc('occupied_count')
            ->take(5)
            ->get();

        $monthlyRevenues  = $this->getMonthlyRevenues();

        $latestNotifications = auth()->user()
            ->appNotifications()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProperties', 'totalUnits', 'occupiedUnits', 'occupancyRate',
            'monthlyRevenue', 'lateInvoices', 'activeLeases',
            'latestInvoices', 'topProperties', 'monthlyRevenues', 'latestNotifications'
        ));
    }

    private function getMonthlyRevenues(): array
    {
        $revenues = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenues[] = [
                'month' => $date->locale('ar')->translatedFormat('M'),
                'year'  => $date->year,
                'total' => Invoice::where('status', 'paid')
                    ->whereMonth('paid_date', $date->month)
                    ->whereYear('paid_date', $date->year)
                    ->sum('amount'),
            ];
        }
        return $revenues;
    }
}
