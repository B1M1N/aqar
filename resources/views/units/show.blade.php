@extends('layouts.app')
@section('title', 'الوحدة ' . $unit->unit_number)
@section('page-title', 'تفاصيل الوحدة')
@section('breadcrumb')
    <a href="{{ route('units.index') }}" class="hover:text-gray-700">الوحدات</a>
    <span class="mx-1">/</span><span class="text-gray-700">{{ $unit->unit_number }}</span>
@endsection

@php
$typeLabels   = ['apartment' => 'شقة', 'studio' => 'استوديو', 'room' => 'غرفة', 'floor' => 'طابق', 'shop' => 'محل', 'suite' => 'جناح'];
$statusClass  = ['available' => 'status-available', 'occupied' => 'status-occupied', 'reserved' => 'status-reserved', 'maintenance' => 'status-maintenance'];
$statusLabel  = ['available' => 'متاحة', 'occupied' => 'مشغولة', 'reserved' => 'محجوزة', 'maintenance' => 'صيانة'];
$periodLabels = ['monthly' => 'شهري', 'quarterly' => 'ربع سنوي', 'yearly' => 'سنوي'];
$leaseStatus  = ['active' => 'status-active-lease', 'expired' => 'status-expired', 'terminated' => 'status-terminated', 'pending' => 'status-pending'];
$leaseLabel   = ['active' => 'نشط', 'expired' => 'منتهي', 'terminated' => 'مُنهى', 'pending' => 'معلق'];
$invStatus    = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
$invLabel     = ['paid' => 'مدفوع', 'pending' => 'غير مدفوع', 'late' => 'متأخر', 'draft' => 'مسودة', 'cancelled' => 'ملغى'];
$mntStatus    = ['open' => 'status-pending', 'in_progress' => 'status-occupied', 'resolved' => 'status-available', 'cancelled' => 'status-cancelled'];
$mntLabel     = ['open' => 'مفتوح', 'in_progress' => 'قيد التنفيذ', 'resolved' => 'منتهي', 'cancelled' => 'ملغى'];
@endphp

@section('content')
<div class="space-y-6" x-data="{ tab: 'info' }">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $unit->unit_number }}</h2>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('properties.show', $unit->property) }}"
                       class="text-indigo-600 hover:text-indigo-700">{{ $unit->property->name }}</a>
                    · {{ $typeLabels[$unit->type] ?? $unit->type }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="{{ $statusClass[$unit->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                {{ $statusLabel[$unit->status] ?? $unit->status }}
            </span>
            @can('units.edit')
            <a href="{{ route('units.edit', $unit) }}" class="btn-secondary btn-sm">تعديل</a>
            @endcan
            @can('units.delete')
            <form method="POST" action="{{ route('units.destroy', $unit) }}"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm">حذف</button>
            </form>
            @endcan
        </div>
    </div>

    {{-- Tab Nav --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-6 -mb-px">
            @foreach([['info','معلومات الوحدة'],['leases','عقود الإيجار'],['invoices','الفواتير'],['maintenance','الصيانة']] as [$key,$label])
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                {{ $label }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- Tab: Info --}}
    <div x-show="tab === 'info'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Details card --}}
            <div class="lg:col-span-2 card p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">تفاصيل الوحدة</h3>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-400 mb-0.5">رقم الوحدة</dt>
                        <dd class="font-semibold text-gray-800">{{ $unit->unit_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">النوع</dt>
                        <dd class="font-medium text-gray-700">{{ $typeLabels[$unit->type] ?? $unit->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الطابق</dt>
                        <dd class="font-medium text-gray-700">{{ $unit->floor }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">المساحة</dt>
                        <dd class="font-medium text-gray-700">{{ $unit->area }} م²</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">غرف النوم</dt>
                        <dd class="font-medium text-gray-700">{{ $unit->bedrooms }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الحمامات</dt>
                        <dd class="font-medium text-gray-700">{{ $unit->bathrooms }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">سعر الإيجار</dt>
                        <dd class="font-semibold text-gray-800">{{ number_format($unit->rent_price) }} ر.س</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">فترة الإيجار</dt>
                        <dd class="font-medium text-gray-700">{{ $periodLabels[$unit->rent_period] ?? $unit->rent_period }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الحالة</dt>
                        <dd>
                            <span class="{{ $statusClass[$unit->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                {{ $statusLabel[$unit->status] ?? $unit->status }}
                            </span>
                        </dd>
                    </div>
                </dl>

                @if($unit->features && count($unit->features))
                <div>
                    <p class="text-xs text-gray-400 mb-2">المميزات</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($unit->features as $f)
                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full">{{ $f }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($unit->notes)
                <div>
                    <p class="text-xs text-gray-400 mb-1">ملاحظات</p>
                    <p class="text-sm text-gray-600">{{ $unit->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Active lease / available --}}
            <div class="space-y-4">
                @if($unit->activeLease)
                <div class="card p-5 space-y-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">العقد النشط</p>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 text-sm font-bold">
                            {{ mb_substr($unit->activeLease->tenant->name ?? '؟', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $unit->activeLease->tenant->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $unit->activeLease->tenant->phone ?? '' }}</p>
                        </div>
                    </div>
                    <dl class="text-sm space-y-1.5">
                        <div class="flex justify-between">
                            <dt class="text-gray-400">من</dt>
                            <dd class="font-medium text-gray-700">{{ $unit->activeLease->start_date->format('Y/m/d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-400">إلى</dt>
                            <dd class="font-medium text-gray-700">{{ $unit->activeLease->end_date->format('Y/m/d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-400">الإيجار</dt>
                            <dd class="font-semibold text-gray-800">{{ number_format($unit->activeLease->rent_amount) }} ر.س</dd>
                        </div>
                    </dl>
                    @can('leases.view')
                    <a href="{{ route('leases.show', $unit->activeLease) }}"
                       class="btn-secondary btn-sm w-full justify-center">عرض العقد</a>
                    @endcan
                </div>
                @else
                <div class="card p-5 text-center space-y-2">
                    <div class="mx-auto h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">الوحدة متاحة للإيجار</p>
                    @can('leases.create')
                    <a href="{{ route('leases.create', ['unit_id' => $unit->id]) }}"
                       class="btn-primary btn-sm w-full justify-center">إنشاء عقد إيجار</a>
                    @endcan
                </div>
                @endif
            </div>
        </div>

        {{-- Images --}}
        @if($unit->images && count($unit->images))
        <div class="card p-6 mt-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">صور الوحدة</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($unit->images as $img)
                <a href="{{ Storage::url($img) }}" target="_blank">
                    <img src="{{ Storage::url($img) }}" alt=""
                         class="w-full h-36 object-cover rounded-xl border border-gray-200 hover:opacity-90 transition">
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Tab: Leases --}}
    <div x-show="tab === 'leases'" x-transition>
        <div class="card">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">عقود الإيجار</h3>
                @can('leases.create')
                <a href="{{ route('leases.create', ['unit_id' => $unit->id]) }}" class="btn-primary btn-sm">
                    + عقد جديد
                </a>
                @endcan
            </div>
            @if($unit->leases->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد عقود بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>المستأجر</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الإيجار</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unit->leases as $lease)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-medium text-gray-800">{{ $lease->tenant->name ?? '—' }}</td>
                            <td class="text-gray-500">{{ $lease->start_date->format('Y/m/d') }}</td>
                            <td class="text-gray-500">{{ $lease->end_date->format('Y/m/d') }}</td>
                            <td class="font-medium text-gray-800">{{ number_format($lease->rent_amount) }} ر.س</td>
                            <td>
                                <span class="{{ $leaseStatus[$lease->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                    {{ $leaseLabel[$lease->status] ?? $lease->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('leases.view')
                                <a href="{{ route('leases.show', $lease) }}"
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

    {{-- Tab: Invoices --}}
    <div x-show="tab === 'invoices'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">آخر الفواتير</h3>
            </div>
            @if($unit->invoices->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد فواتير بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>المستأجر</th>
                            <th>المبلغ</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unit->invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
                            <td class="text-gray-700">{{ optional($invoice->lease->tenant)->name ?? '—' }}</td>
                            <td class="font-medium text-gray-800">{{ number_format($invoice->amount) }} ر.س</td>
                            <td class="text-gray-500">{{ $invoice->due_date->format('Y/m/d') }}</td>
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

    {{-- Tab: Maintenance --}}
    <div x-show="tab === 'maintenance'" x-transition>
        <div class="card">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">طلبات الصيانة</h3>
                @can('maintenance.create')
                <a href="{{ route('maintenance.create', ['unit_id' => $unit->id]) }}" class="btn-primary btn-sm">
                    + طلب جديد
                </a>
                @endcan
            </div>
            @if($unit->maintenanceRequests->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد طلبات صيانة</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unit->maintenanceRequests as $req)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-medium text-gray-800">{{ $req->title }}</td>
                            <td>
                                <span class="priority-{{ $req->priority }}">
                                    {{ ['low'=>'منخفض','medium'=>'متوسط','high'=>'عالي','urgent'=>'عاجل'][$req->priority] ?? $req->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $mntStatus[$req->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                                    {{ $mntLabel[$req->status] ?? $req->status }}
                                </span>
                            </td>
                            <td class="text-gray-500">{{ $req->created_at->format('Y/m/d') }}</td>
                            <td class="text-end">
                                @can('maintenance.view')
                                <a href="{{ route('maintenance.show', $req) }}"
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

</div>
@endsection
