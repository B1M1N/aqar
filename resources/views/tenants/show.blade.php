@extends('layouts.app')
@section('title', $tenant->name)
@section('page-title', 'تفاصيل المستأجر')
@section('breadcrumb')
    <a href="{{ route('tenants.index') }}" class="hover:text-gray-700">المستأجرون</a>
    <span class="mx-1">/</span><span class="text-gray-700">{{ $tenant->name }}</span>
@endsection

@php
$idTypeLabel = ['national_id' => 'هوية وطنية', 'passport' => 'جواز سفر', 'iqama' => 'إقامة'];
$leaseStatus = ['active' => 'status-active-lease', 'expired' => 'status-expired', 'terminated' => 'status-terminated', 'pending' => 'status-pending'];
$leaseLabel  = ['active' => 'نشط', 'expired' => 'منتهي', 'terminated' => 'مُنهى', 'pending' => 'معلق'];
$invStatus   = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
$invLabel    = ['paid' => 'مدفوع', 'pending' => 'غير مدفوع', 'late' => 'متأخر', 'draft' => 'مسودة', 'cancelled' => 'ملغى'];
$mntStatus   = ['open' => 'status-pending', 'in_progress' => 'status-occupied', 'resolved' => 'status-available', 'cancelled' => 'status-cancelled'];
$mntLabel    = ['open' => 'مفتوح', 'in_progress' => 'قيد التنفيذ', 'resolved' => 'منتهي', 'cancelled' => 'ملغى'];
@endphp

@section('content')
<div class="space-y-6" x-data="{ tab: 'info' }">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-xl font-bold">
                {{ mb_substr($tenant->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h2>
                <p class="text-sm text-gray-500">{{ $tenant->email }} · {{ $tenant->phone }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('tenants.edit')
            <a href="{{ route('tenants.edit', $tenant) }}" class="btn-secondary btn-sm">تعديل</a>
            @endcan
            @can('tenants.delete')
            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm">حذف</button>
            </form>
            @endcan
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-6 -mb-px">
            @foreach([['info','البيانات'],['leases','العقود'],['invoices','الفواتير'],['maintenance','الصيانة']] as [$key,$label])
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

            <div class="lg:col-span-2 card p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">المعلومات الشخصية</h3>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-400 mb-0.5">الاسم</dt>
                        <dd class="font-semibold text-gray-800">{{ $tenant->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الهاتف</dt>
                        <dd class="font-medium text-gray-700">{{ $tenant->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">البريد</dt>
                        <dd class="font-medium text-gray-700 break-all">{{ $tenant->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الجنسية</dt>
                        <dd class="font-medium text-gray-700">{{ $tenant->nationality ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">نوع الهوية</dt>
                        <dd class="font-medium text-gray-700">{{ $idTypeLabel[$tenant->id_type] ?? $tenant->id_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">رقم الهوية</dt>
                        <dd class="font-mono text-gray-700">{{ $tenant->national_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">انتهاء الهوية</dt>
                        <dd class="font-medium text-gray-700">
                            @if($tenant->id_expiry)
                                {{ $tenant->id_expiry->format('Y/m/d') }}
                                @if($tenant->id_expiry->isPast())
                                    <span class="status-late text-xs ms-1">منتهية</span>
                                @elseif($tenant->id_expiry->diffInDays(now()) < 60)
                                    <span class="status-reserved text-xs ms-1">قريبًا</span>
                                @endif
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">طوارئ</dt>
                        <dd class="font-medium text-gray-700">{{ $tenant->emergency_contact ?? '—' }}</dd>
                    </div>
                </dl>

                @if($tenant->notes)
                <div>
                    <p class="text-xs text-gray-400 mb-1">ملاحظات</p>
                    <p class="text-sm text-gray-600">{{ $tenant->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Active lease --}}
            <div class="space-y-4">
                @if($tenant->activeLease)
                <div class="card p-5 space-y-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">الوحدة الحالية</p>
                    <div>
                        <p class="font-semibold text-indigo-600 hover:text-indigo-700">
                            <a href="{{ route('units.show', $tenant->activeLease->unit) }}">
                                {{ $tenant->activeLease->unit->unit_number }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $tenant->activeLease->unit->property->name }}
                        </p>
                    </div>
                    <dl class="text-sm space-y-1.5">
                        <div class="flex justify-between">
                            <dt class="text-gray-400">من</dt>
                            <dd class="font-medium text-gray-700">{{ $tenant->activeLease->start_date->format('Y/m/d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-400">إلى</dt>
                            <dd class="font-medium text-gray-700">{{ $tenant->activeLease->end_date->format('Y/m/d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-400">الإيجار</dt>
                            <dd class="font-semibold text-gray-800">{{ number_format($tenant->activeLease->rent_amount) }} ر.س</dd>
                        </div>
                    </dl>
                    @can('leases.view')
                    <a href="{{ route('leases.show', $tenant->activeLease) }}" class="btn-secondary btn-sm w-full justify-center">
                        عرض العقد
                    </a>
                    @endcan
                </div>
                @else
                <div class="card p-5 text-center">
                    <p class="text-sm text-gray-400">لا يوجد عقد إيجار نشط</p>
                    @can('leases.create')
                    <a href="{{ route('leases.create', ['tenant_id' => $tenant->id]) }}"
                       class="btn-primary btn-sm mt-3 inline-flex">إنشاء عقد إيجار</a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab: Leases --}}
    <div x-show="tab === 'leases'" x-transition>
        <div class="card">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">سجل العقود</h3>
                @can('leases.create')
                <a href="{{ route('leases.create', ['tenant_id' => $tenant->id]) }}" class="btn-primary btn-sm">
                    + عقد جديد
                </a>
                @endcan
            </div>
            @if($tenant->leases->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد عقود بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>الوحدة</th>
                            <th>العقار</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الإيجار</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenant->leases as $lease)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-medium text-gray-800">{{ $lease->unit->unit_number ?? '—' }}</td>
                            <td class="text-gray-500 text-sm">{{ $lease->unit->property->name ?? '—' }}</td>
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
            @if($tenant->invoices->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد فواتير بعد</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>المبلغ</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenant->invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
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
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">طلبات الصيانة</h3>
            </div>
            @if($tenant->maintenanceRequests->isEmpty())
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
                        @foreach($tenant->maintenanceRequests as $req)
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
