@extends('layouts.app')
@section('title', 'عقود الإيجار')
@section('page-title', 'عقود الإيجار')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">عقود الإيجار</span>
@endsection

@php
$statusClass = ['active' => 'status-active-lease', 'pending' => 'status-pending', 'expired' => 'status-expired', 'terminated' => 'status-terminated'];
$statusLabel = ['active' => 'نشط', 'pending' => 'معلق', 'expired' => 'منتهي', 'terminated' => 'مُنهى'];
@endphp

@section('content')
<div class="space-y-5">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">إجمالي {{ $leases->total() }} عقد</p>
        @can('leases.create')
        <a href="{{ route('leases.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            إضافة عقد
        </a>
        @endcan
    </div>

    <div class="card p-4">
        <form method="GET" action="{{ route('leases.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-48">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="اسم المستأجر أو رقم الوحدة" class="form-input">
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
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('leases.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($leases->isEmpty())
        <div class="p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-3 text-gray-500">لا توجد عقود مطابقة</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>المستأجر</th>
                        <th>الوحدة</th>
                        <th>العقار</th>
                        <th>من</th>
                        <th>إلى</th>
                        <th>الإيجار</th>
                        <th>يوم السداد</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leases as $lease)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-medium text-gray-800">{{ $lease->tenant->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('units.show', $lease->unit) }}"
                               class="text-indigo-600 hover:text-indigo-700 font-medium">
                                {{ $lease->unit->unit_number }}
                            </a>
                        </td>
                        <td class="text-gray-500 text-sm">{{ $lease->unit->property->name }}</td>
                        <td class="text-gray-500">{{ $lease->start_date->format('Y/m/d') }}</td>
                        <td class="text-gray-500">{{ $lease->end_date->format('Y/m/d') }}</td>
                        <td class="font-medium text-gray-800">{{ number_format($lease->rent_amount) }} ر.س</td>
                        <td class="text-gray-500 text-center">{{ $lease->payment_day }}</td>
                        <td>
                            <span class="{{ $statusClass[$lease->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$lease->status] ?? $lease->status }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('leases.show', $lease) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                                @can('leases.edit')
                                <a href="{{ route('leases.edit', $lease) }}"
                                   class="text-gray-500 hover:text-gray-700 text-sm">تعديل</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-100 p-4">
            {{ $leases->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
