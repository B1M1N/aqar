@extends('layouts.app')
@section('title', 'الصيانة')
@section('page-title', 'طلبات الصيانة')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">الصيانة</span>
@endsection

@php
$statusClass   = ['open' => 'status-pending', 'in_progress' => 'status-occupied', 'resolved' => 'status-available', 'cancelled' => 'status-cancelled'];
$statusLabel   = ['open' => 'مفتوح', 'in_progress' => 'قيد التنفيذ', 'resolved' => 'منتهي', 'cancelled' => 'ملغى'];
$priorityLabel = ['low' => 'منخفض', 'medium' => 'متوسط', 'high' => 'عالي', 'urgent' => 'عاجل'];
$typeLabel     = ['electrical' => 'كهرباء', 'plumbing' => 'سباكة', 'hvac' => 'تكييف', 'structural' => 'هيكلي', 'appliance' => 'أجهزة', 'other' => 'أخرى'];
@endphp

@section('content')
<div class="space-y-5">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">إجمالي {{ $requests->total() }} طلب</p>
        @can('maintenance.create')
        <a href="{{ route('maintenance.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            طلب جديد
        </a>
        @endcan
    </div>

    <div class="card p-4">
        <form method="GET" action="{{ route('maintenance.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-40">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="عنوان الطلب" class="form-input">
            </div>
            <div class="w-44">
                <label class="form-label">الوحدة</label>
                <select name="unit_id" class="form-input">
                    <option value="">الكل</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" @selected(request('unit_id') == $u->id)>
                            {{ $u->property->name }} / {{ $u->unit_number }}
                        </option>
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
            <div class="w-32">
                <label class="form-label">الأولوية</label>
                <select name="priority" class="form-input">
                    <option value="">الكل</option>
                    @foreach($priorityLabel as $val => $label)
                        <option value="{{ $val }}" @selected(request('priority') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">بحث</button>
            @if(request()->hasAny(['search','status','priority','unit_id']))
                <a href="{{ route('maintenance.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($requests->isEmpty())
        <div class="p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="mt-3 text-gray-500">لا توجد طلبات صيانة مطابقة</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الوحدة</th>
                        <th>النوع</th>
                        <th>الأولوية</th>
                        <th>المُعيَّن</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-medium text-gray-800">{{ $req->title }}</td>
                        <td class="text-sm text-gray-600">
                            <a href="{{ route('units.show', $req->unit) }}"
                               class="text-indigo-600 hover:text-indigo-700">{{ $req->unit->unit_number }}</a>
                            <span class="text-gray-400"> · {{ $req->unit->property->name }}</span>
                        </td>
                        <td class="text-gray-500 text-sm">{{ $typeLabel[$req->type] ?? $req->type }}</td>
                        <td><span class="priority-{{ $req->priority }}">{{ $priorityLabel[$req->priority] ?? $req->priority }}</span></td>
                        <td class="text-gray-500 text-sm">{{ $req->assignedTo->name ?? '—' }}</td>
                        <td>
                            <span class="{{ $statusClass[$req->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$req->status] ?? $req->status }}
                            </span>
                        </td>
                        <td class="text-gray-500 text-sm">{{ $req->created_at->format('Y/m/d') }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('maintenance.show', $req) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                                @can('maintenance.edit')
                                <a href="{{ route('maintenance.edit', $req) }}"
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
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
