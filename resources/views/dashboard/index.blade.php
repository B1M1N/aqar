@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">

    {{-- KPI Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="kpi-card">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProperties }}</p>
                <p class="text-sm text-gray-500">إجمالي العقارات</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyRevenue) }}</p>
                <p class="text-sm text-gray-500">الإيرادات هذا الشهر (ر.س)</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $occupancyRate }}%</p>
                <p class="text-sm text-gray-500">نسبة الإشغال الكلية</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $occupiedUnits }} من {{ $totalUnits }} وحدة</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $lateInvoices }}</p>
                <p class="text-sm text-gray-500">فواتير متأخرة</p>
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">

        {{-- Revenue chart + Latest invoices --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Revenue bar chart (CSS only) --}}
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-4">الإيرادات الشهرية — آخر 12 شهراً</h3>
                @php $maxRevenue = collect($monthlyRevenues)->max('total') ?: 1; @endphp
                <div class="flex items-end gap-1.5 h-40">
                    @foreach($monthlyRevenues as $m)
                    @php $pct = $maxRevenue > 0 ? ($m['total'] / $maxRevenue) * 100 : 0; @endphp
                    <div class="group flex flex-1 flex-col items-center gap-1">
                        <div class="relative w-full flex items-end justify-center" style="height: 128px">
                            <div class="w-full rounded-t-md bg-indigo-500 hover:bg-indigo-600 transition"
                                 style="height: {{ max($pct, 2) }}%"
                                 title="{{ number_format($m['total']) }} ر.س"></div>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $m['month'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-400">
                    <span>0</span>
                    <span>{{ number_format($maxRevenue / 2) }}</span>
                    <span>{{ number_format($maxRevenue) }} ر.س</span>
                </div>
            </div>

            {{-- Latest invoices --}}
            <div class="card">
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">آخر الفواتير</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">عرض الكل</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead><tr>
                            <th>المستأجر</th><th>الوحدة</th><th>المبلغ</th><th>تاريخ الاستحقاق</th><th>الحالة</th>
                        </tr></thead>
                        <tbody>
                            @forelse($latestInvoices as $invoice)
                            @php
                                $stClass = ['paid' => 'status-paid', 'pending' => 'status-pending', 'late' => 'status-late', 'draft' => 'status-draft', 'cancelled' => 'status-cancelled'];
                                $stLabel = ['paid' => 'مدفوعة', 'pending' => 'معلقة', 'late' => 'متأخرة', 'draft' => 'مسودة', 'cancelled' => 'ملغية'];
                            @endphp
                            <tr>
                                <td class="font-medium text-gray-800">{{ optional($invoice->tenant)->name ?? '—' }}</td>
                                <td class="text-gray-500">{{ optional($invoice->unit)->unit_number ?? '—' }}</td>
                                <td class="font-medium">{{ number_format($invoice->amount) }} ر.س</td>
                                <td class="text-gray-500 text-xs">{{ $invoice->due_date->format('Y/m/d') }}</td>
                                <td><span class="{{ $stClass[$invoice->status] ?? 'badge bg-gray-100' }}">{{ $stLabel[$invoice->status] ?? $invoice->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-400">لا توجد فواتير</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar: top properties + notifications --}}
        <div class="space-y-5">

            {{-- Top properties by occupancy --}}
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-4">أعلى العقارات إشغالاً</h3>
                @forelse($topProperties as $p)
                @php $rate = $p->total_count > 0 ? round(($p->occupied_count / $p->total_count) * 100) : 0; @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-1">
                        <a href="{{ route('properties.show', $p) }}"
                           class="text-sm font-medium text-gray-800 hover:text-indigo-600 truncate max-w-36">{{ $p->name }}</a>
                        <span class="text-xs font-semibold text-gray-600">{{ $rate }}%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-gray-100">
                        <div class="h-2 rounded-full {{ $rate >= 80 ? 'bg-emerald-500' : ($rate >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                             style="width: {{ $rate }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $p->occupied_count }}/{{ $p->total_count }} وحدة</p>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">لا توجد عقارات</p>
                @endforelse
            </div>

            {{-- Latest notifications --}}
            <div class="card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">آخر الإشعارات</h3>
                    <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">الكل</a>
                </div>
                @forelse($latestNotifications as $n)
                <div class="flex gap-3 py-2.5 border-b border-gray-50 last:border-0">
                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full {{ $n->read_at ? 'bg-gray-100' : 'bg-indigo-100' }}">
                        <svg class="h-3.5 w-3.5 {{ $n->read_at ? 'text-gray-400' : 'text-indigo-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                        </svg>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $n->title }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $n->body }}</p>
                        <p class="text-xs text-gray-300 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">لا توجد إشعارات</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
