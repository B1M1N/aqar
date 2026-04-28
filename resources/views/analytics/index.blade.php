@extends('layouts.app')
@section('title', 'التحليلات')
@section('page-title', 'لوحة التحليلات')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">التحليلات</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Unit Status KPIs --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
        $kpis = [
            ['label' => 'متاحة',      'key' => 'available',    'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
            ['label' => 'مشغولة',     'key' => 'occupied',     'color' => 'text-blue-600',    'bg' => 'bg-blue-50'],
            ['label' => 'محجوزة',     'key' => 'reserved',     'color' => 'text-amber-600',   'bg' => 'bg-amber-50'],
            ['label' => 'صيانة',      'key' => 'maintenance',  'color' => 'text-orange-600',  'bg' => 'bg-orange-50'],
        ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="kpi-card">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $kpi['bg'] }} {{ $kpi['color'] }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $unitStats[$kpi['key']] ?? 0 }}</p>
                <p class="text-sm text-gray-500">{{ $kpi['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Revenue Chart --}}
    <div class="card p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">الإيرادات — آخر 12 شهراً</h3>
        <div x-data="{
            chart: null,
            init() {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'الإيرادات (ر.س)',
                            data: @json($revenues),
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('ar') } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }">
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Occupancy per property --}}
        <div class="card p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">نسبة الإشغال حسب العقار</h3>
            @if($properties->isEmpty())
            <p class="text-gray-400 text-sm">لا توجد عقارات</p>
            @else
            <div class="space-y-3">
                @foreach($properties as $property)
                @php
                    $rate = $property->units_count > 0
                        ? round(($property->occupied_count / $property->units_count) * 100)
                        : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-800">{{ $property->name }}</span>
                        <span class="text-gray-500">{{ $property->occupied_count }}/{{ $property->units_count }} ({{ $rate }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all"
                             style="width: {{ $rate }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Maintenance breakdown --}}
        <div class="card p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">توزيع طلبات الصيانة</h3>
            <div x-data="{
                init() {
                    const ctx = document.getElementById('mntChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: @json(collect(['open'=>'مفتوح','in_progress'=>'قيد التنفيذ','resolved'=>'منتهي','cancelled'=>'ملغى'])->only($maintenanceStats->keys())->values()),
                            datasets: [{
                                data: @json($maintenanceStats->values()),
                                backgroundColor: ['#f59e0b','#3b82f6','#10b981','#6b7280'],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } },
                            cutout: '60%'
                        }
                    });
                }
            }">
                <canvas id="mntChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Expiring leases --}}
    @if($expiringLeases->isNotEmpty())
    <div class="card">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">عقود تنتهي خلال 30 يوماً</h3>
            <span class="badge bg-red-100 text-red-700">{{ $expiringLeases->count() }} عقد</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>المستأجر</th>
                        <th>الوحدة</th>
                        <th>تاريخ الانتهاء</th>
                        <th>المتبقي</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiringLeases as $lease)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-medium text-gray-800">{{ $lease->tenant->name }}</td>
                        <td class="text-gray-500 text-sm">{{ $lease->unit->unit_number }} · {{ $lease->unit->property->name }}</td>
                        <td class="text-gray-500">{{ $lease->end_date->format('Y/m/d') }}</td>
                        <td>
                            @php $days = now()->diffInDays($lease->end_date, false); @endphp
                            <span class="{{ $days <= 7 ? 'status-late' : 'status-reserved' }}">
                                {{ $days }} يوم
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('leases.show', $lease) }}"
                               class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Late invoices --}}
    @if($lateInvoices->isNotEmpty())
    <div class="card">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">فواتير متأخرة</h3>
            <span class="badge bg-red-100 text-red-700">{{ $lateInvoices->count() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>الفاتورة</th>
                        <th>المستأجر</th>
                        <th>المبلغ</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>التأخر</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lateInvoices as $invoice)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="font-mono text-gray-700">{{ $invoice->invoice_number }}</td>
                        <td class="font-medium text-gray-800">{{ $invoice->tenant->name }}</td>
                        <td class="font-semibold text-gray-800">{{ number_format($invoice->amount) }} ر.س</td>
                        <td class="text-gray-500">{{ $invoice->due_date->format('Y/m/d') }}</td>
                        <td>
                            <span class="status-late">{{ now()->diffInDays($invoice->due_date) }} يوم</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('invoices.show', $invoice) }}"
                               class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
@endpush
