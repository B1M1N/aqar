<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $isUser = auth()->user()->hasRole('user');

        $properties = Property::query()
            ->when($isUser, fn ($q) => $q->where('owner_id', auth()->id()))
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%")
            )
            ->when($request->type,   fn ($q) => $q->where('type', $request->type))
            ->when($request->city,   fn ($q) => $q->where('city', $request->city))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->withCount([
                'units as total_units_count',
                'units as occupied_units_count' => fn ($q) => $q->where('status', 'occupied'),
                'units as available_units_count' => fn ($q) => $q->where('status', 'available'),
            ])
            ->with('owner')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $cities = $isUser
            ? Property::where('owner_id', auth()->id())->distinct()->pluck('city')->sort()->values()
            : Property::distinct()->pluck('city')->sort()->values();

        return view('properties.index', compact('properties', 'cities'));
    }

    public function create(): View
    {
        return view('properties.create');
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['owner_id'] = auth()->id();
        $data['images']   = $this->uploadImages($request, []);
        $data['amenities'] = $request->input('amenities', []);

        $property = Property::create($data);

        return redirect()->route('properties.show', $property)
            ->with('success', 'تم إنشاء العقار بنجاح.');
    }

    public function show(Property $property): View
    {
        if (auth()->user()->hasRole('user') && $property->owner_id !== auth()->id()) {
            abort(403);
        }

        $property->load(['owner', 'units.activeLease.tenant']);

        $maintenanceCount = MaintenanceRequest::whereIn(
            'unit_id', $property->units->pluck('id')
        )->open()->count();

        $monthlyRevenue = $property->units->sum(
            fn ($u) => optional($u->activeLease)->rent_amount ?? 0
        );

        $stats = [
            'total_units'      => $property->total_units,
            'available_units'  => $property->available_units,
            'occupancy_rate'   => $property->occupancy_rate,
            'monthly_revenue'  => $monthlyRevenue,
            'maintenance_open' => $maintenanceCount,
        ];

        return view('properties.show', compact('property', 'stats'));
    }

    public function edit(Property $property): View
    {
        return view('properties.edit', compact('property'));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $data = $request->validated();

        // Remove individually deleted images
        $existing = $property->images ?? [];
        foreach ($request->input('remove_images', []) as $path) {
            Storage::disk('public')->delete($path);
            $existing = array_filter($existing, fn ($i) => $i !== $path);
        }

        // Append newly uploaded images
        $newImages      = $this->uploadImages($request, []);
        $data['images'] = array_values(array_merge($existing, $newImages));
        $data['amenities'] = $request->input('amenities', []);

        $property->update($data);

        return redirect()->route('properties.show', $property)
            ->with('success', 'تم تحديث العقار بنجاح.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'تم حذف العقار بنجاح.');
    }

    private function uploadImages(Request $request, array $existing): array
    {
        $paths = $existing;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('properties', 'public');
            }
        }

        return $paths;
    }
}
