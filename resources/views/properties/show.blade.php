@extends('layouts.app')
@section('title', $property->name)
@section('page-title', $property->name)
@section('breadcrumb')
    <a href="{{ route('properties.index') }}" class="hover:text-gray-600">العقارات</a>
    <span class="mx-1">/</span><span class="text-gray-700">{{ $property->name }}</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header card --}}
    <div class="card overflow-visible">
        {{-- Hero image --}}
        @if($property->images && count($property->images) > 0)
        <div class="h-56 overflow-hidden" x-data="{ active: 0 }">
            <template x-for="(img, i) in {{ json_encode($property->images) }}" :key="i">
                <img :src="'/storage/' + img"
                     x-show="active === i"
                     class="h-full w-full object-cover">
            </template>
            @if(count($property->images) > 1)
            <div class="absolute bottom-3 start-1/2 -translate-x-1/2 flex gap-1.5">
                @foreach($property->images as $i => $img)
                <button @click="active = {{ $i }}"
                        :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'"
                        class="h-2 w-2 rounded-full transition"></button>
                @endforeach
            </div>
            @endif
        </div>
        @else
        <div class="h-40 flex items-center justify-center bg-gradient-to-br from-indigo-50 to-indigo-100">
            <svg class="h-16 w-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
        </div>
        @endif

        <div class="p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl font-bold text-gray-900">{{ $property->name }}</h1>
                        @php
                            $typeLabels = ['building' => 'مبنى', 'apartment' => 'شقة', 'villa' => 'فيلا', 'hotel' => 'فندق'];
                        @endphp
                        <span class="badge bg-indigo-100 text-indigo-700">{{ $typeLabels[$property->type] ?? $property->type }}</span>
                        <span class="status-{{ $property->status }}">
                            {{ ['active' => 'نشط', 'inactive' => 'غير نشط', 'under_maintenance' => 'تحت الصيانة'][$property->status] ?? $property->status }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $property->address }}
                        @if($property->district) · {{ $property->district }} @endif
                        · {{ $property->city }}
                    </p>
                    @if($property->description)
                    <p class="mt-2 text-sm text-gray-600 max-w-2xl">{{ $property->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    @can('properties.edit')
                    <a href="{{ route('properties.edit', $property) }}" class="btn-secondary btn-sm">تعديل</a>
                    @endcan
                    @can('units.create')
                    <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="btn-primary btn-sm">
                        + إضافة وحدة
                    </a>
                    @endcan
                </div>
            </div>

            {{-- Meta info --}}
            <div class="mt-4 flex flex-wrap gap-5 text-sm text-gray-500">
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v13H3V7z"/>
                    </svg>
                    {{ $property->floors }} طابق
                </span>
                @if($property->build_year)
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    بُني {{ $property->build_year }}
                </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $property->owner->name }}
                </span>
            </div>

            {{-- Amenities --}}
            @if($property->amenities && count($property->amenities) > 0)
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($property->amenities as $amenity)
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600">
                    <svg class="h-3 w-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ $amenity }}
                </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        <div class="kpi-card flex-col items-center justify-center text-center sm:col-span-1">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_units'] }}</p>
            <p class="text-xs text-gray-400 mt-1">إجمالي الوحدات</p>
        </div>
        <div class="kpi-card flex-col items-center justify-center text-center">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['available_units'] }}</p>
            <p class="text-xs text-gray-400 mt-1">متاحة</p>
        </div>
        <div class="kpi-card flex-col items-center justify-center text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_units'] - $stats['available_units'] }}</p>
            <p class="text-xs text-gray-400 mt-1">مشغولة</p>
        </div>
        <div class="kpi-card flex-col items-center justify-center text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['occupancy_rate'] }}%</p>
            <p class="text-xs text-gray-400 mt-1">نسبة الإشغال</p>
        </div>
        <div class="kpi-card flex-col items-center justify-center text-center">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['monthly_revenue']) }}</p>
            <p class="text-xs text-gray-400 mt-1">إيراد شهري (ر.س)</p>
        </div>
    </div>

    {{-- Units table --}}
    <div class="card">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">الوحدات ({{ $property->units->count() }})</h3>
            @can('units.create')
            <a href="{{ route('units.create', ['property_id' => $property->id]) }}" class="btn-primary btn-sm">
                + إضافة وحدة
            </a>
            @endcan
        </div>
        @if($property->units->isEmpty())
            <div class="p-12 text-center text-gray-400">لا توجد وحدات بعد</div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>رقم الوحدة</th>
                        <th>النوع</th>
                        <th>الطابق</th>
                        <th>المساحة</th>
                        <th>الإيجار</th>
                        <th>الحالة</th>
                        <th>المستأجر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($property->units as $unit)
                    @php
                        $typeLabels = ['apartment' => 'شقة', 'studio' => 'استوديو', 'room' => 'غرفة', 'floor' => 'طابق', 'shop' => 'محل', 'suite' => 'جناح'];
                        $statusClass = ['available' => 'status-available', 'occupied' => 'status-occupied', 'reserved' => 'status-reserved', 'maintenance' => 'status-maintenance'];
                        $statusLabel = ['available' => 'متاحة', 'occupied' => 'مشغولة', 'reserved' => 'محجوزة', 'maintenance' => 'صيانة'];
                    @endphp
                    <tr>
                        <td class="font-medium text-gray-900">{{ $unit->unit_number }}</td>
                        <td>{{ $typeLabels[$unit->type] ?? $unit->type }}</td>
                        <td>{{ $unit->floor }}</td>
                        <td>{{ $unit->area }} م²</td>
                        <td>{{ number_format($unit->rent_price) }} ر.س</td>
                        <td><span class="{{ $statusClass[$unit->status] ?? 'badge bg-gray-100 text-gray-700' }}">{{ $statusLabel[$unit->status] ?? $unit->status }}</span></td>
                        <td class="text-sm text-gray-500">
                            {{ optional(optional($unit->activeLease)->tenant)->name ?? '—' }}
                        </td>
                        <td>
                            @can('units.view')
                            <a href="{{ route('units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
