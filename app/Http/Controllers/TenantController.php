<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $tenants = Tenant::query()
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('national_id', 'like', "%{$request->search}%")
            )
            ->withCount('leases')
            ->with('activeLease.unit.property')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('tenants.create');
    }

    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $tenant = Tenant::create($request->validated());

        return redirect()->route('tenants.show', $tenant)
            ->with('success', 'تم إضافة المستأجر بنجاح.');
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load([
            'activeLease.unit.property',
            'leases.unit.property',
            'invoices'            => fn ($q) => $q->latest()->take(10),
            'maintenanceRequests' => fn ($q) => $q->latest()->take(10),
        ]);

        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $tenant->update($request->validated());

        return redirect()->route('tenants.show', $tenant)
            ->with('success', 'تم تحديث بيانات المستأجر بنجاح.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'تم حذف المستأجر بنجاح.');
    }
}
