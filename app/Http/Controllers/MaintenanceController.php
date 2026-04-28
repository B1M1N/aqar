<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $requests = MaintenanceRequest::query()
            ->when($request->search, fn ($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->when($request->status,   fn ($q) => $q->where('status', $request->status))
            ->when($request->priority, fn ($q) => $q->where('priority', $request->priority))
            ->when($request->unit_id,  fn ($q) => $q->where('unit_id', $request->unit_id))
            ->with(['unit.property', 'tenant', 'assignedTo'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $units = Unit::with('property')->get(['id', 'unit_number', 'property_id']);

        return view('maintenance.index', compact('requests', 'units'));
    }

    public function create(Request $request): View
    {
        $units   = Unit::with('property')->get(['id', 'unit_number', 'property_id']);
        $tenants = Tenant::orderBy('name')->get(['id', 'name']);
        $staff   = User::role(['admin', 'manager', 'staff'])->get(['id', 'name']);

        $selectedUnit = $request->unit_id ? Unit::find($request->unit_id) : null;

        return view('maintenance.create', compact('units', 'tenants', 'staff', 'selectedUnit'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('maintenance.create');

        $data = $request->validate([
            'unit_id'      => ['required', 'exists:units,id'],
            'tenant_id'    => ['nullable', 'exists:tenants,id'],
            'assigned_to'  => ['nullable', 'exists:users,id'],
            'title'        => ['required', 'string', 'max:200'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'type'         => ['required', 'in:electrical,plumbing,hvac,structural,appliance,other'],
            'priority'     => ['required', 'in:low,medium,high,urgent'],
            'status'       => ['required', 'in:open,in_progress,resolved,cancelled'],
            'cost'         => ['nullable', 'numeric', 'min:0'],
            'scheduled_at' => ['nullable', 'date'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['images'] = $this->uploadImages($request);

        MaintenanceRequest::create($data);

        return redirect()->route('maintenance.index')
            ->with('success', 'تم إنشاء طلب الصيانة بنجاح.');
    }

    public function show(MaintenanceRequest $maintenance): View
    {
        $maintenance->load(['unit.property', 'tenant', 'assignedTo', 'updates.user']);

        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRequest $maintenance): View
    {
        $units   = Unit::with('property')->get(['id', 'unit_number', 'property_id']);
        $tenants = Tenant::orderBy('name')->get(['id', 'name']);
        $staff   = User::role(['admin', 'manager', 'staff'])->get(['id', 'name']);

        return view('maintenance.edit', compact('maintenance', 'units', 'tenants', 'staff'));
    }

    public function update(Request $request, MaintenanceRequest $maintenance): RedirectResponse
    {
        $this->authorize('maintenance.edit');

        $data = $request->validate([
            'unit_id'      => ['required', 'exists:units,id'],
            'tenant_id'    => ['nullable', 'exists:tenants,id'],
            'assigned_to'  => ['nullable', 'exists:users,id'],
            'title'        => ['required', 'string', 'max:200'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'type'         => ['required', 'in:electrical,plumbing,hvac,structural,appliance,other'],
            'priority'     => ['required', 'in:low,medium,high,urgent'],
            'status'       => ['required', 'in:open,in_progress,resolved,cancelled'],
            'cost'         => ['nullable', 'numeric', 'min:0'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $maintenance->update($data);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'تم تحديث الطلب بنجاح.');
    }

    public function destroy(MaintenanceRequest $maintenance): RedirectResponse
    {
        $this->authorize('maintenance.delete');
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'تم حذف الطلب بنجاح.');
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenance): RedirectResponse
    {
        $this->authorize('maintenance.update-status');

        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,cancelled'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $maintenance->update(['status' => $data['status']]);

        if (!empty($data['note'])) {
            $maintenance->updates()->create([
                'user_id'           => auth()->id(),
                'note'              => $data['note'],
                'status_changed_to' => $data['status'],
            ]);
        }

        return back()->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function assign(Request $request, MaintenanceRequest $maintenance): RedirectResponse
    {
        $this->authorize('maintenance.assign');

        $request->validate(['assigned_to' => ['required', 'exists:users,id']]);

        $maintenance->update([
            'assigned_to' => $request->assigned_to,
            'status'      => $maintenance->status === 'open' ? 'in_progress' : $maintenance->status,
        ]);

        return back()->with('success', 'تم تعيين الفني بنجاح.');
    }

    private function uploadImages(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('maintenance', 'public');
            }
        }
        return $paths;
    }
}
