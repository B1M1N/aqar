@extends('layouts.app')
@section('title', 'العقارات')
@section('page-title', 'العقارات')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">العقارات</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500">إجمالي {{ $properties->total() }} عقار</p>
        </div>
        @can('properties.create')
        <a href="{{ route('properties.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            إضافة عقار
        </a>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('properties.index') }}"
              class="flex flex-wrap items-end gap-3" x-data>
            <div class="flex-1 min-w-48">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="اسم العقار، المدينة..."
                       class="form-input">
            </div>
            <div class="w-40">
                <label class="form-label">النوع</label>
                <select name="type" class="form-input">
                    <option value="">الكل</option>
                    @foreach(['building' => 'مبنى', 'apartment' => 'شقة', 'villa' => 'فيلا', 'hotel' => 'فندق'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label class="form-label">المدينة</label>
                <select name="city" class="form-input">
                    <option value="">الكل</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-input">
                    <option value="">الكل</option>
                    <option value="active"            @selected(request('status') === 'active')>نشط</option>
                    <option value="inactive"          @selected(request('status') === 'inactive')>غير نشط</option>
                    <option value="under_maintenance" @selected(request('status') === 'under_maintenance')>تحت الصيانة</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">بحث</button>
            @if(request()->hasAny(['search','type','city','status']))
                <a href="{{ route('properties.index') }}" class="btn-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>

    {{-- Grid --}}
    @if($properties->isEmpty())
        <div class="card p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
            <p class="mt-3 text-gray-500">لا توجد عقارات مطابقة</p>
            @can('properties.create')
            <a href="{{ route('properties.create') }}" class="btn-primary mt-4 inline-flex">إضافة أول عقار</a>
            @endcan
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($properties as $property)
            <div class="card group flex flex-col">
                {{-- Image --}}
                <div class="relative h-44 overflow-hidden bg-gray-100">
                    @if($property->images && count($property->images) > 0)
                        <img src="{{ Storage::url($property->images[0]) }}"
                             alt="{{ $property->name }}"
                             class="h-full w-full object-cover transition group-hover:scale-105 duration-300">
                    @else
                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-indigo-50 to-indigo-100">
                            <svg class="h-14 w-14 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                    @endif
                    {{-- Status badge --}}
                    <div class="absolute top-2 start-2">
                        @php
                            $statusMap = [
                                'active'            => ['class' => 'bg-emerald-500', 'label' => 'نشط'],
                                'inactive'          => ['class' => 'bg-gray-400',    'label' => 'غير نشط'],
                                'under_maintenance' => ['class' => 'bg-amber-500',   'label' => 'صيانة'],
                            ];
                            $s = $statusMap[$property->status] ?? ['class' => 'bg-gray-400', 'label' => $property->status];
                        @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium text-white {{ $s['class'] }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-white/70"></span>
                            {{ $s['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="flex flex-1 flex-col p-4">
                    <div class="mb-3">
                        <h3 class="font-semibold text-gray-900 leading-snug">{{ $property->name }}</h3>
                        <p class="mt-1 text-xs text-gray-500">
                            <span>{{ $property->city }}</span>
                            @if($property->district) <span> · {{ $property->district }}</span> @endif
                        </p>
                    </div>

                    {{-- Occupancy bar --}}
                    @php
                        $rate  = $property->total_units_count > 0
                            ? round(($property->occupied_units_count / $property->total_units_count) * 100)
                            : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>الإشغال</span>
                            <span class="font-medium text-gray-700">{{ $rate }}%</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-gray-100">
                            <div class="h-1.5 rounded-full {{ $rate >= 80 ? 'bg-emerald-500' : ($rate >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                 style="width: {{ $rate }}%"></div>
                        </div>
                    </div>

                    {{-- Stats row --}}
                    <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                        <div class="rounded-lg bg-gray-50 px-2 py-1.5">
                            <p class="text-lg font-bold text-gray-800">{{ $property->total_units_count }}</p>
                            <p class="text-xs text-gray-400">وحدة</p>
                        </div>
                        <div class="rounded-lg bg-emerald-50 px-2 py-1.5">
                            <p class="text-lg font-bold text-emerald-700">{{ $property->available_units_count }}</p>
                            <p class="text-xs text-emerald-500">متاحة</p>
                        </div>
                        <div class="rounded-lg bg-blue-50 px-2 py-1.5">
                            <p class="text-lg font-bold text-blue-700">{{ $property->occupied_units_count }}</p>
                            <p class="text-xs text-blue-500">مشغولة</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-auto flex items-center gap-2">
                        <a href="{{ route('properties.show', $property) }}"
                           class="btn-primary btn-sm flex-1 justify-center">عرض</a>
                        @can('properties.edit')
                        <a href="{{ route('properties.edit', $property) }}"
                           class="btn-secondary btn-sm justify-center px-3">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @endcan
                        @can('properties.delete')
                        <form method="POST" action="{{ route('properties.destroy', $property) }}"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا العقار؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm justify-center px-3">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-2">{{ $properties->links() }}</div>
    @endif

</div>
@endsection
