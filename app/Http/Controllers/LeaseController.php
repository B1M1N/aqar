<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Services\LeaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeaseController extends Controller
{
    public function __construct(private LeaseService $leaseService) {}

    public function index(Request $request): View
    {
        // [role:user] scope to leases belonging to the user's linked tenant record
        $tenantId = null;
        if (auth()->user()->hasRole('user')) {
            $tenantId = auth()->user()->tenant?->id;
        }

        $leases = Lease::query()
            ->when($tenantId,   fn ($q) => $q->where('tenant_id', $tenantId))
            ->when($tenantId === null && auth()->user()->hasRole('user'), fn ($q) =>
                $q->whereRaw('0 = 1')
            )
            ->when($request->search, fn ($q) =>
                $q->whereHas('tenant', fn ($t) => $t->where('name', 'like', "%{$request->search}%"))
                  ->orWhereHas('unit', fn ($u) => $u->where('unit_number', 'like', "%{$request->search}%"))
            )
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->with(['unit.property', 'tenant'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('leases.index', compact('leases'));
    }

    public function create(Request $request): View
    {
        $units   = Unit::where('status', 'available')->with('property')->get();
        $tenants = Tenant::orderBy('name')->get(['id', 'name', 'phone']);

        $selectedUnit   = $request->unit_id   ? Unit::find($request->unit_id)     : null;
        $selectedTenant = $request->tenant_id ? Tenant::find($request->tenant_id) : null;

        return view('leases.create', compact('units', 'tenants', 'selectedUnit', 'selectedTenant'));
    }

    public function store(StoreLeaseRequest $request): RedirectResponse
    {
        $lease = Lease::create($request->validated());

        return redirect()->route('leases.show', $lease)
            ->with('success', 'تم إنشاء عقد الإيجار بنجاح.');
    }

    public function show(Lease $lease): View
    {
        $lease->load(['unit.property', 'tenant', 'renewals', 'invoices' => fn ($q) => $q->latest()]);

        // [role:user] allow access only to their own lease via linked tenant record
        if (auth()->user()->hasRole('user')) {
            $tenantId = auth()->user()->tenant?->id;
            if (! $tenantId || $lease->tenant_id !== $tenantId) {
                return redirect()->route('leases.index');
            }
        }

        return view('leases.show', compact('lease'));
    }

    public function edit(Lease $lease): View
    {
        $units   = Unit::with('property')->get();
        $tenants = Tenant::orderBy('name')->get(['id', 'name', 'phone']);

        return view('leases.edit', compact('lease', 'units', 'tenants'));
    }

    public function update(UpdateLeaseRequest $request, Lease $lease): RedirectResponse
    {
        $lease->update($request->validated());

        return redirect()->route('leases.show', $lease)
            ->with('success', 'تم تحديث العقد بنجاح.');
    }

    public function destroy(Lease $lease): RedirectResponse
    {
        $lease->delete();

        return redirect()->route('leases.index')
            ->with('success', 'تم حذف العقد بنجاح.');
    }

    public function terminate(Request $request, Lease $lease): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:500']]);
        $this->authorize('leases.terminate');

        $this->leaseService->terminate($lease, $request->reason);

        return back()->with('success', 'تم إنهاء العقد بنجاح.');
    }

    public function renew(Request $request, Lease $lease): RedirectResponse
    {
        $request->validate([
            'new_end_date'    => ['required', 'date', 'after:' . $lease->end_date->format('Y-m-d')],
            'new_rent_amount' => ['required', 'numeric', 'min:0'],
        ]);
        $this->authorize('leases.renew');

        $this->leaseService->renew($lease, $request->all());

        return back()->with('success', 'تم تجديد العقد بنجاح.');
    }

    public function generatePdf(Lease $lease)
    {
        $this->authorize('leases.generate-pdf');

        $path = $this->leaseService->generatePdf($lease);

        return Storage::disk('public')->download($path, 'عقد-' . $lease->id . '.pdf');
    }
}
