<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(Request $request): View
    {
        $units = Unit::query()
            ->when($request->search, fn ($q) =>
                $q->where('unit_number', 'like', "%{$request->search}%")
            )
            ->when($request->property_id, fn ($q) =>
                $q->where('property_id', $request->property_id)
            )
            ->when($request->type,   fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->with(['property', 'activeLease.tenant'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $properties = Property::orderBy('name')->get(['id', 'name']);

        return view('units.index', compact('units', 'properties'));
    }

    public function create(Request $request): View
    {
        $properties     = Property::orderBy('name')->get(['id', 'name']);
        $selectedProperty = $request->property_id
            ? Property::find($request->property_id)
            : null;

        return view('units.create', compact('properties', 'selectedProperty'));
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['features'] = $request->input('features', []);
        $data['images']   = $this->uploadImages($request);

        $unit = Unit::create($data);

        return redirect()->route('units.show', $unit)
            ->with('success', 'تم إنشاء الوحدة بنجاح.');
    }

    public function show(Unit $unit): View
    {
        $unit->load([
            'property',
            'activeLease.tenant',
            'leases.tenant',
            'invoices' => fn ($q) => $q->latest()->take(10),
            'maintenanceRequests' => fn ($q) => $q->latest()->take(10),
        ]);

        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        $properties = Property::orderBy('name')->get(['id', 'name']);

        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $data = $request->validated();

        // Handle image removals
        $existing = $unit->images ?? [];
        foreach ($request->input('remove_images', []) as $path) {
            Storage::disk('public')->delete($path);
            $existing = array_filter($existing, fn ($i) => $i !== $path);
        }

        $data['images']   = array_values(array_merge($existing, $this->uploadImages($request)));
        $data['features'] = $request->input('features', []);

        $unit->update($data);

        return redirect()->route('units.show', $unit)
            ->with('success', 'تم تحديث الوحدة بنجاح.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $propertyId = $unit->property_id;
        $unit->delete();

        return redirect()->route('properties.show', $propertyId)
            ->with('success', 'تم حذف الوحدة بنجاح.');
    }

    private function uploadImages(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('units', 'public');
            }
        }
        return $paths;
    }
}
