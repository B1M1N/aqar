@extends('layouts.app')
@section('title', 'المستأجرون')
@section('page-title', 'المستأجرون')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">المستأجرون</span>
@endsection

@php
$idTypeLabel = ['national_id' => 'هوية وطنية', 'passport' => 'جواز سفر', 'iqama' => 'إقامة'];
@endphp

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">إجمالي {{ $tenants->total() }} مستأجر</p>
        @can('tenants.create')
        <a href="{{ route('tenants.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            إضافة مستأجر
        </a>
        @endcan
    </div>

    {{-- Search --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('tenants.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-48">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="الاسم، الهاتف، رقم الهوية" class="form-input">
            </div>
            <button type="submit" class="btn-primary">بحث</button>
            @if(request('search'))
                <a href="{{ route('tenants.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        @if($tenants->isEmpty())
        <div class="p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="mt-3 text-gray-500">لا يوجد مستأجرون مطابقون</p>
            @can('tenants.create')
            <a href="{{ route('tenants.create') }}" class="btn-primary mt-4 inline-flex">إضافة أول مستأجر</a>
            @endcan
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد</th>
                        <th>نوع الهوية</th>
                        <th>رقم الهوية</th>
                        <th>الوحدة الحالية</th>
                        <th>عدد العقود</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                    <tr class="hover:bg-gray-50 transition">
                        <td>
                            <a href="{{ route('tenants.show', $tenant) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-700">
                                {{ $tenant->name }}
                            </a>
                        </td>
                        <td class="text-gray-600">{{ $tenant->phone }}</td>
                        <td class="text-gray-500 text-xs">{{ $tenant->email }}</td>
                        <td class="text-gray-500 text-sm">{{ $idTypeLabel[$tenant->id_type] ?? $tenant->id_type }}</td>
                        <td class="font-mono text-gray-600 text-sm">{{ $tenant->national_id }}</td>
                        <td class="text-sm text-gray-600">
                            @if($tenant->activeLease?->unit)
                                <a href="{{ route('units.show', $tenant->activeLease->unit) }}"
                                   class="text-indigo-600 hover:text-indigo-700">
                                    {{ $tenant->activeLease->unit->unit_number }}
                                </a>
                                <span class="text-gray-400"> — {{ $tenant->activeLease->unit->property->name }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="text-center text-gray-600">{{ $tenant->leases_count }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('tenants.show', $tenant) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                                @can('tenants.edit')
                                <a href="{{ route('tenants.edit', $tenant) }}"
                                   class="text-gray-500 hover:text-gray-700 text-sm">تعديل</a>
                                @endcan
                                @can('tenants.delete')
                                <form method="POST" action="{{ route('tenants.destroy', $tenant) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
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
            {{ $tenants->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
