@extends('layouts.app')
@section('title', 'التنبؤات الذكية')
@section('page-title', 'التنبؤات الذكية')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">التنبؤات الذكية</span>
@endsection

@section('content')
<div class="space-y-8" x-data="{ tab: 'risk' }">

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-6 -mb-px">
            @foreach([
                ['risk',    'خطر التأخر في السداد'],
                ['vacancy', 'توقعات الشواغر'],
                ['maint',   'الصيانة الوقائية'],
            ] as [$key,$label])
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                {{ $label }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- Tab: Late Payment Risk --}}
    <div x-show="tab === 'risk'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">خطر التأخر في السداد</h3>
                <p class="text-sm text-gray-500 mt-0.5">تنبؤ بالمستأجرين الأكثر عرضة للتأخر في دفع الإيجار</p>
            </div>
            @if($riskData->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد بيانات كافية لتحليل المخاطر</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>المستأجر</th>
                            <th>الوحدة</th>
                            <th>فواتير متأخرة</th>
                            <th>مستوى الخطر</th>
                            <th>درجة المخاطرة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riskData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td>
                                <a href="{{ route('tenants.show', $row['tenant']) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-700">
                                    {{ $row['tenant']->name }}
                                </a>
                            </td>
                            <td class="text-gray-500 text-sm">
                                {{ $row['tenant']->activeLease->unit->unit_number }}
                                · {{ $row['tenant']->activeLease->unit->property->name }}
                            </td>
                            <td class="text-center text-gray-700">
                                {{ $row['late_count'] }} / {{ $row['total_inv'] }}
                            </td>
                            <td>
                                @if($row['risk_level'] === 'high')
                                    <span class="status-late">عالي</span>
                                @elseif($row['risk_level'] === 'medium')
                                    <span class="status-reserved">متوسط</span>
                                @else
                                    <span class="status-available">منخفض</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all
                                            {{ $row['score'] >= 70 ? 'bg-red-500' : ($row['score'] >= 40 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                             style="width: {{ $row['score'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 w-8 text-end">{{ $row['score'] }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Tab: Vacancy Forecast --}}
    <div x-show="tab === 'vacancy'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">توقعات الوحدات الشاغرة</h3>
                <p class="text-sm text-gray-500 mt-0.5">الوحدات المتاحة مرتبة حسب مدة الشغور</p>
            </div>
            @if($vacancyData->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد وحدات شاغرة حالياً</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>الوحدة</th>
                            <th>العقار</th>
                            <th>أيام الشغور</th>
                            <th>متوسط التأجير</th>
                            <th>احتمالية الملء</th>
                            <th>الأولوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacancyData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td>
                                <a href="{{ route('units.show', $row['unit']) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-700">
                                    {{ $row['unit']->unit_number }}
                                </a>
                            </td>
                            <td class="text-gray-500 text-sm">{{ $row['unit']->property->name }}</td>
                            <td class="font-semibold text-gray-800">{{ $row['days_vacant'] }} يوم</td>
                            <td class="text-gray-500 text-sm">{{ $row['avg_days_lease'] }} يوم</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-indigo-400 transition-all"
                                             style="width: {{ min(100,$row['fill_likelihood']) }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-8 text-end">{{ min(100,$row['fill_likelihood']) }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($row['urgency'] === 'high')
                                    <span class="status-late">عاجل</span>
                                @elseif($row['urgency'] === 'medium')
                                    <span class="status-reserved">متوسط</span>
                                @else
                                    <span class="status-available">عادي</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Tab: Preventive Maintenance --}}
    <div x-show="tab === 'maint'" x-transition>
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">الصيانة الوقائية</h3>
                <p class="text-sm text-gray-500 mt-0.5">وحدات يُتوقع أنها تحتاج إلى صيانة دورية</p>
            </div>
            @if($maintData->isEmpty())
            <div class="p-12 text-center text-gray-400 text-sm">لا توجد بيانات صيانة تاريخية كافية</div>
            @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>الوحدة</th>
                            <th>العقار</th>
                            <th>آخر صيانة</th>
                            <th>الدورة المعتادة</th>
                            <th>أيام منذ الصيانة</th>
                            <th>درجة الاستحقاق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintData as $row)
                        <tr class="{{ $row['overdue'] ? 'bg-red-50' : '' }} hover:bg-gray-50 transition">
                            <td>
                                <a href="{{ route('units.show', $row['unit']) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-700">
                                    {{ $row['unit']->unit_number }}
                                </a>
                                @if($row['overdue'])
                                    <span class="status-late text-xs ms-1">متأخرة</span>
                                @endif
                            </td>
                            <td class="text-gray-500 text-sm">{{ $row['unit']->property->name }}</td>
                            <td class="text-gray-500">{{ $row['last_done'] }}</td>
                            <td class="text-gray-500 text-sm">كل {{ $row['avg_interval'] }} يوم</td>
                            <td class="font-semibold text-gray-800">{{ $row['days_since'] }} يوم</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all
                                            {{ $row['due_score'] >= 90 ? 'bg-red-500' : ($row['due_score'] >= 60 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                             style="width: {{ min(100,$row['due_score']) }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 w-8 text-end">{{ min(100,$row['due_score']) }}</span>
                                </div>
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
