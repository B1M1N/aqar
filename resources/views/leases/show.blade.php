@extends('layouts.app')
@section('title', 'عقد الإيجار #' . $lease->id)
@section('page-title', 'تفاصيل عقد الإيجار')
@section('breadcrumb')
    <a href="{{ route('leases.index') }}" class="hover:text-gray-700">عقود الإيجار</a>
    <span class="mx-1">/</span><span class="text-gray-700">#{{ $lease->id }}</span>
@endsection

@php
$statusClass = ['active' => 'status-active-lease', 'pending' => 'status-pending', 'expired' => 'status-expired', 'terminated' => 'status-terminated'];
$statusLabel = ['active' => 'نشط', 'pending' => 'معلق', 'expired' => 'منتهي', 'terminated' => 'مُنهى'];
$invStatus   = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
$invLabel    = ['paid' => 'مدفوع', 'pending' => 'غير مدفوع', 'late' => 'متأخر', 'draft' => 'مسودة', 'cancelled' => 'ملغى'];
@endphp

@section('content')
<div class="space-y-6" x-data="{ tab: 'details', showTerminate: false, showRenew: false }">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">عقد #{{ $lease->id }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $lease->tenant->name }} ·
                @can('units.view')
                <a href="{{ route('units.show', $lease->unit) }}" class="text-indigo-600 hover:text-indigo-700">
                    {{ $lease->unit->unit_number }}
                </a>
                @else
                <span>{{ $lease->unit->unit_number }}</span>
                @endcan
                · {{ $lease->unit->property->name }}
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="{{ $statusClass[$lease->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                {{ $statusLabel[$lease->status] ?? $lease->status }}
            </span>

            @can('leases.generate-pdf')
            <a href="{{ route('leases.pdf', $lease) }}" class="btn-secondary btn-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                تنزيل PDF
            </a>
            @endcan

            @can('leases.renew')
            @if($lease->status === 'active' || $lease->status === 'expired')
            <button @click="showRenew = true" class="btn-secondary btn-sm">تجديد العقد</button>
            @endif
            @endcan

            @can('leases.terminate')
            @if($lease->status === 'active' || $lease->status === 'pending')
            <button @click="showTerminate = true" class="btn-danger btn-sm">إنهاء العقد</button>
            @endif
            @endcan

            @can('leases.edit')
            <a href="{{ route('leases.edit', $lease) }}" class="btn-secondary btn-sm">تعديل</a>
            @endcan
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-6 -mb-px">
            @foreach([['details','تفاصيل العقد'],['invoices','الفواتير'],['renewals','التجديدات']] as [$key,$label])
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                {{ $label }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- Tab: Details --}}
    <div x-show="tab === 'details'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">بيانات العقد</h3>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-400 mb-0.5">تاريخ البداية</dt>
                        <dd class="font-semibold text-gray-800">{{ $lease->start_date->format('Y/m/d') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">تاريخ الانتهاء</dt>
                        <dd class="font-semibold text-gray-800">{{ $lease->end_date->format('Y/m/d') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">مبلغ الإيجار</dt>
                        <dd class="font-semibold text-gray-800">{{ number_format($lease->rent_amount) }} ر.س</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">مبلغ التأمين</dt>
                        <dd class="font-medium text-gray-700">{{ number_format($lease->deposit_amount ?? 0) }} ر.س</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">يوم السداد</dt>
                        <dd class="font-medium text-gray-700">اليوم {{ $lease->payment_day }} من كل شهر</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الحالة</dt>
                        <dd>
                            <span class="{{ $statusClass[$lease->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$lease->status] ?? $lease->status }}
                            </span>
                        </dd>
                    </div>
                </dl>
                @if($lease->notes)
                <div>
                    <p class="text-xs text-gray-400 mb-1">ملاحظات</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $lease->notes }}</p>
                </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="card p-5 space-y-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">المستأجر</p>
                    <p class="font-semibold text-gray-800">{{ $lease->tenant->name }}</p>
                    <p class="text-sm text-gray-500">{{ $lease->tenant->phone }}</p>
                    <p class="text-sm text-gray-500">{{ $lease->tenant->email }}</p>
                    @can('tenants.view')
                    <a href="{{ route('tenants.show', $lease->tenant) }}" class="btn-secondary btn-sm inline-flex">
                        عرض ملف المستأجر
                    </a>
                    @endcan
                </div>

                <div class="card p-5 space-y-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">الوحدة</p>
                    <p class="font-semibold text-gray-800">
                        @can('units.view')
                        <a href="{{ route('units.show', $lease->unit) }}" class="text-indigo-600 hover:text-indigo-700">
                            {{ $lease->unit->unit_number }}
                        </a>
                        @else
                        {{ $lease->unit->unit_number }}
                        @endcan
                    </p>
                    <p class="text-sm text-gray-500">{{ $lease->unit->property->name }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab: Invoices --}}
    <div x-show="tab === 'invoices'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">فواتير العقد</h3>
            </div>
            @if($lease->invoices->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد فواتير بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>المبلغ</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>تاريخ السداد</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lease->invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
                            <td class="font-medium text-gray-800">{{ number_format($invoice->amount) }} ر.س</td>
                            <td class="text-gray-500">{{ $invoice->due_date->format('Y/m/d') }}</td>
                            <td class="text-gray-500">{{ $invoice->paid_at?->format('Y/m/d') ?? '—' }}</td>
                            <td>
                                @php $st = $invoice->is_late ? 'late' : $invoice->status; @endphp
                                <span class="{{ $invStatus[$st] ?? 'badge bg-gray-100 text-gray-700' }}">
                                    {{ $invLabel[$st] ?? $invoice->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('invoices.view')
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
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

    {{-- Tab: Renewals --}}
    <div x-show="tab === 'renewals'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">سجل التجديدات</h3>
            </div>
            @if($lease->renewals->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لم يُجدَّد هذا العقد بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>الانتهاء القديم</th>
                            <th>الانتهاء الجديد</th>
                            <th>الإيجار الجديد</th>
                            <th>تاريخ التجديد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lease->renewals as $renewal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="text-gray-500">{{ $renewal->old_end_date->format('Y/m/d') }}</td>
                            <td class="text-gray-500">{{ $renewal->new_end_date->format('Y/m/d') }}</td>
                            <td class="font-medium text-gray-800">{{ number_format($renewal->new_rent_amount) }} ر.س</td>
                            <td class="text-gray-500">{{ $renewal->created_at->format('Y/m/d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal: Terminate --}}
    <div x-show="showTerminate" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="showTerminate = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">إنهاء العقد</h3>
            <form method="POST" action="{{ route('leases.terminate', $lease) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="form-label">سبب الإنهاء <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="3" class="form-input" required
                              placeholder="يرجى ذكر سبب إنهاء العقد..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showTerminate = false" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-danger">تأكيد الإنهاء</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Renew --}}
    <div x-show="showRenew" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="showRenew = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">تجديد العقد</h3>
            <form method="POST" action="{{ route('leases.renew', $lease) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="form-label">تاريخ الانتهاء الجديد <span class="text-red-500">*</span></label>
                    <input type="date" name="new_end_date"
                           value="{{ $lease->end_date->addYear()->format('Y-m-d') }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">الإيجار الجديد (ر.س) <span class="text-red-500">*</span></label>
                    <input type="number" name="new_rent_amount" value="{{ $lease->rent_amount }}"
                           min="0" step="0.01" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">ملاحظات</label>
                    <input type="text" name="notes" class="form-input" placeholder="اختياري">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showRenew = false" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-primary">تأكيد التجديد</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
