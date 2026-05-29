@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page-title', 'تفاصيل الفاتورة')
@section('breadcrumb')
    <a href="{{ route('invoices.index') }}" class="hover:text-gray-700">الفواتير</a>
    <span class="mx-1">/</span><span class="text-gray-700">{{ $invoice->invoice_number }}</span>
@endsection

@php
$statusClass = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
$statusLabel = ['paid' => 'مدفوع', 'pending' => 'غير مدفوع', 'late' => 'متأخر', 'draft' => 'مسودة', 'cancelled' => 'ملغى'];
$typeLabel   = ['rent' => 'إيجار', 'maintenance' => 'صيانة', 'utility' => 'خدمات', 'other' => 'أخرى'];
$methodLabel = ['cash' => 'نقدًا', 'bank_transfer' => 'تحويل بنكي', 'check' => 'شيك', 'moyasar' => 'إلكتروني'];
$st          = $invoice->is_late ? 'late' : $invoice->status;
@endphp

@section('content')
<div class="space-y-6" x-data="{ showPayModal: false }">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $invoice->tenant->name ?? '—' }} ·
                <a href="{{ route('units.show', $invoice->unit) }}" class="text-indigo-600 hover:text-indigo-700">
                    {{ $invoice->unit->unit_number }}
                </a>
                · {{ $invoice->unit->property->name }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="{{ $statusClass[$st] ?? 'badge bg-gray-100 text-gray-700' }}">
                {{ $statusLabel[$st] ?? $invoice->status }}
            </span>

            @can('invoices.generate-pdf')
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-secondary btn-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                تنزيل PDF
            </a>
            @endcan

            @can('invoices.pay')
            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
            <button @click="showPayModal = true" class="btn-primary btn-sm">تسجيل دفعة</button>
            @endif
            @endcan

            @can('invoices.edit')
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn-secondary btn-sm">تعديل</a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Invoice details --}}
        <div class="lg:col-span-2 card p-6 space-y-5">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">تفاصيل الفاتورة</h3>
            <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-400 mb-0.5">رقم الفاتورة</dt>
                    <dd class="font-mono font-semibold text-gray-800">{{ $invoice->invoice_number }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 mb-0.5">المبلغ</dt>
                    <dd class="font-bold text-lg text-indigo-600">{{ number_format($invoice->amount) }} ر.س</dd>
                </div>
                <div>
                    <dt class="text-gray-400 mb-0.5">النوع</dt>
                    <dd class="font-medium text-gray-700">{{ $typeLabel[$invoice->type] ?? $invoice->type }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 mb-0.5">تاريخ الاستحقاق</dt>
                    <dd class="font-medium text-gray-700">{{ $invoice->due_date->format('Y/m/d') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 mb-0.5">تاريخ السداد</dt>
                    <dd class="font-medium text-gray-700">{{ $invoice->paid_date?->format('Y/m/d') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 mb-0.5">الحالة</dt>
                    <dd>
                        <span class="{{ $statusClass[$st] ?? 'badge bg-gray-100 text-gray-700' }}">
                            {{ $statusLabel[$st] ?? $invoice->status }}
                        </span>
                    </dd>
                </div>
            </dl>

            @if($invoice->notes)
            <div>
                <p class="text-xs text-gray-400 mb-1">ملاحظات</p>
                <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar info --}}
        <div class="space-y-4">
            <div class="card p-5 space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">المستأجر</p>
                <p class="font-semibold text-gray-800">{{ $invoice->tenant->name }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->tenant->phone }}</p>
                @can('tenants.view')
                <a href="{{ route('tenants.show', $invoice->tenant) }}" class="btn-secondary btn-sm inline-flex mt-1">
                    ملف المستأجر
                </a>
                @endcan
            </div>
            @if($invoice->lease)
            <div class="card p-5 space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">العقد</p>
                <p class="text-sm text-gray-700">
                    من {{ $invoice->lease->start_date->format('Y/m/d') }}
                    إلى {{ $invoice->lease->end_date->format('Y/m/d') }}
                </p>
                @can('leases.view')
                <a href="{{ route('leases.show', $invoice->lease) }}" class="btn-secondary btn-sm inline-flex mt-1">
                    عرض العقد
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>

    {{-- Payment History --}}
    @if($invoice->payments->isNotEmpty())
    <div class="card">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">سجل المدفوعات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th>رقم العملية</th>
                        <th>بواسطة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="text-gray-500">{{ $payment->created_at->format('Y/m/d H:i') }}</td>
                        <td class="font-semibold text-gray-800">{{ number_format($payment->amount) }} ر.س</td>
                        <td class="text-gray-600">{{ $methodLabel[$payment->method] ?? $payment->method }}</td>
                        <td class="font-mono text-gray-500 text-xs">{{ $payment->transaction_id ?? '—' }}</td>
                        <td class="text-gray-500">{{ $payment->paidBy->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Pay Modal --}}
    <div x-show="showPayModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="showPayModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">تسجيل دفعة</h3>
            <form method="POST" action="{{ route('invoices.pay', $invoice) }}" class="space-y-4">
                @csrf @method('POST')
                <div>
                    <label class="form-label">طريقة الدفع <span class="text-red-500">*</span></label>
                    <select name="method" class="form-input" required>
                        <option value="cash">نقدًا</option>
                        <option value="bank_transfer">تحويل بنكي</option>
                        <option value="check">شيك</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">رقم المرجع / العملية</label>
                    <input type="text" name="transaction_id" class="form-input" placeholder="اختياري">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showPayModal = false" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-primary">تأكيد الدفع</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
