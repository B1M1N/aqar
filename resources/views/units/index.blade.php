@extends('layouts.app')
@section('title', 'الوحدات')
@section('page-title', 'الوحدات')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">الوحدات</span>
@endsection

@php
$typeLabels   = ['apartment' => 'شقة', 'studio' => 'استوديو', 'room' => 'غرفة', 'floor' => 'طابق', 'shop' => 'محل', 'suite' => 'جناح'];
$statusClass  = ['available' => 'status-available', 'occupied' => 'status-occupied', 'reserved' => 'status-reserved', 'maintenance' => 'status-maintenance'];
$statusLabel  = ['available' => 'متاحة', 'occupied' => 'مشغولة', 'reserved' => 'محجوزة', 'maintenance' => 'صيانة'];
$periodLabels = ['monthly' => 'شهري', 'quarterly' => 'ربع سنوي', 'yearly' => 'سنوي'];
@endphp

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">إجمالي {{ $units->total() }} وحدة</p>
        @can('units.create')
        <a href="{{ route('units.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            إضافة وحدة
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('units.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-40">
                <label class="form-label">بحث برقم الوحدة</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="مثال: A-101" class="form-input">
            </div>
            <div class="w-44">
                <label class="form-label">العقار</label>
                <select name="property_id" class="form-input">
                    <option value="">الكل</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}" @selected(request('property_id') == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="form-label">النوع</label>
                <select name="type" class="form-input">
                    <option value="">الكل</option>
                    @foreach($typeLabels as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-input">
                    <option value="">الكل</option>
                    @foreach($statusLabel as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">بحث</button>
            @if(request()->hasAny(['search','property_id','type','status']))
                <a href="{{ route('units.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        @if($units->isEmpty())
            <div class="p-16 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/>
                </svg>
                <p class="mt-3 text-gray-500">لا توجد وحدات مطابقة</p>
                @can('units.create')
                <a href="{{ route('units.create') }}" class="btn-primary mt-4 inline-flex">إضافة أول وحدة</a>
                @endcan
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>العقار</th>
                        <th>رقم الوحدة</th>
                        <th>النوع</th>
                        <th>الطابق</th>
                        <th>المساحة</th>
                        <th>الإيجار</th>
                        <th>الفترة</th>
                        <th>الحالة</th>
                        <th>المستأجر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                    <tr class="hover:bg-gray-50 transition">
                        <td>
                            <a href="{{ route('properties.show', $unit->property) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-700">
                                {{ $unit->property->name }}
                            </a>
                        </td>
                        <td class="font-semibold text-gray-900">{{ $unit->unit_number }}</td>
                        <td>{{ $typeLabels[$unit->type] ?? $unit->type }}</td>
                        <td class="text-gray-500">{{ $unit->floor }}</td>
                        <td class="text-gray-500">{{ $unit->area }} م²</td>
                        <td class="font-medium text-gray-800">{{ number_format($unit->rent_price) }} ر.س</td>
                        <td class="text-gray-500 text-xs">{{ $periodLabels[$unit->rent_period] ?? $unit->rent_period }}</td>
                        <td>
                            <span class="{{ $statusClass[$unit->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$unit->status] ?? $unit->status }}
                            </span>
                        </td>
                        <td class="text-sm text-gray-500">
                            {{ optional(optional($unit->activeLease)->tenant)->name ?? '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('units.show', $unit) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                                @can('units.edit')
                                <a href="{{ route('units.edit', $unit) }}"
                                   class="text-gray-500 hover:text-gray-700 text-sm">تعديل</a>
                                @endcan
                                @can('units.delete')
                                <form method="POST" action="{{ route('units.destroy', $unit) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">حذف</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-100 p-4">
            {{ $units->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
