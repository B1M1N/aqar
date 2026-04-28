@extends('layouts.app')
@section('title', 'الفواتير')
@section('page-title', 'الفواتير')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">الفواتير</span>
@endsection

@php
$statusClass = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
$statusLabel = ['paid' => 'مدفوع', 'pending' => 'غير مدفوع', 'late' => 'متأخر', 'draft' => 'مسودة', 'cancelled' => 'ملغى'];
$typeLabel   = ['rent' => 'إيجار', 'maintenance' => 'صيانة', 'utility' => 'خدمات', 'other' => 'أخرى'];
@endphp

@section('content')
<div class="space-y-5">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">إجمالي {{ $invoices->total() }} فاتورة</p>
        @can('invoices.create')
        <a href="{{ route('invoices.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            فاتورة جديدة
        </a>
        @endcan
    </div>

    <div class="card p-4">
        <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-40">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="اسم المستأجر" class="form-input">
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
                <label class="form-label">النوع</label>
                <select name="type" class="form-input">
                    <option value="">الكل</option>
                    @foreach($typeLabel as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">بحث</button>
            @if(request()->hasAny(['search','status','type']))
                <a href="{{ route('invoices.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($invoices->isEmpty())
        <div class="p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 14H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v3m-7 11h7m-7 0v-5m7 5v-5m-7 0h7"/>
            </svg>
            <p class="mt-3 text-gray-500">لا توجد فواتير مطابقة</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المستأجر</th>
                        <th>الوحدة</th>
                        <th>المبلغ</th>
                        <th>النوع</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    @php $st = $invoice->is_late ? 'late' : $invoice->status; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
                        <td class="font-medium text-gray-800">{{ $invoice->tenant->name ?? '—' }}</td>
                        <td class="text-gray-500 text-sm">
                            {{ $invoice->unit->unit_number }}
                            <span class="text-gray-400">· {{ $invoice->unit->property->name }}</span>
                        </td>
                        <td class="font-semibold text-gray-800">{{ number_format($invoice->amount) }} ر.س</td>
                        <td class="text-gray-500 text-sm">{{ $typeLabel[$invoice->type] ?? $invoice->type }}</td>
                        <td class="text-gray-500">{{ $invoice->due_date->format('Y/m/d') }}</td>
                        <td>
                            <span class="{{ $statusClass[$st] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$st] ?? $invoice->status }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                                @can('invoices.edit')
                                <a href="{{ route('invoices.edit', $invoice) }}"
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
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
