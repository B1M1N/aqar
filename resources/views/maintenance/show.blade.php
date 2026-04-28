@extends('layouts.app')
@section('title', 'طلب صيانة #' . $maintenance->id)
@section('page-title', 'تفاصيل طلب الصيانة')
@section('breadcrumb')
    <a href="{{ route('maintenance.index') }}" class="hover:text-gray-700">الصيانة</a>
    <span class="mx-1">/</span><span class="text-gray-700">#{{ $maintenance->id }}</span>
@endsection

@php
$statusClass   = ['open' => 'status-pending', 'in_progress' => 'status-occupied', 'resolved' => 'status-available', 'cancelled' => 'status-cancelled'];
$statusLabel   = ['open' => 'مفتوح', 'in_progress' => 'قيد التنفيذ', 'resolved' => 'منتهي', 'cancelled' => 'ملغى'];
$priorityLabel = ['low' => 'منخفض', 'medium' => 'متوسط', 'high' => 'عالي', 'urgent' => 'عاجل'];
$typeLabel     = ['electrical' => 'كهرباء', 'plumbing' => 'سباكة', 'hvac' => 'تكييف', 'structural' => 'هيكلي', 'appliance' => 'أجهزة', 'other' => 'أخرى'];
@endphp

@section('content')
<div class="space-y-6" x-data="{ showStatusModal: false, showAssignModal: false }">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $maintenance->title }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                <a href="{{ route('units.show', $maintenance->unit) }}" class="text-indigo-600 hover:text-indigo-700">
                    {{ $maintenance->unit->unit_number }}
                </a>
                · {{ $maintenance->unit->property->name }}
                @if($maintenance->tenant)
                · {{ $maintenance->tenant->name }}
                @endif
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="{{ $statusClass[$maintenance->status] ?? 'badge bg-gray-100 text-gray-700' }}">
                {{ $statusLabel[$maintenance->status] ?? $maintenance->status }}
            </span>
            <span class="priority-{{ $maintenance->priority }}">
                {{ $priorityLabel[$maintenance->priority] ?? $maintenance->priority }}
            </span>

            @can('maintenance.update-status')
            <button @click="showStatusModal = true" class="btn-secondary btn-sm">تحديث الحالة</button>
            @endcan

            @can('maintenance.assign')
            <button @click="showAssignModal = true" class="btn-secondary btn-sm">تعيين فني</button>
            @endcan

            @can('maintenance.edit')
            <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn-secondary btn-sm">تعديل</a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">التفاصيل</h3>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-400 mb-0.5">نوع العطل</dt>
                        <dd class="font-medium text-gray-700">{{ $typeLabel[$maintenance->type] ?? $maintenance->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الأولوية</dt>
                        <dd><span class="priority-{{ $maintenance->priority }}">{{ $priorityLabel[$maintenance->priority] ?? $maintenance->priority }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">الحالة</dt>
                        <dd><span class="{{ $statusClass[$maintenance->status] ?? 'badge bg-gray-100 text-gray-700' }}">{{ $statusLabel[$maintenance->status] ?? $maintenance->status }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">التاريخ المقرر</dt>
                        <dd class="font-medium text-gray-700">{{ $maintenance->scheduled_at?->format('Y/m/d H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">تاريخ الإنجاز</dt>
                        <dd class="font-medium text-gray-700">{{ $maintenance->completed_at?->format('Y/m/d H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 mb-0.5">التكلفة</dt>
                        <dd class="font-semibold text-gray-800">{{ $maintenance->cost ? number_format($maintenance->cost) . ' ر.س' : '—' }}</dd>
                    </div>
                </dl>

                @if($maintenance->description)
                <div>
                    <p class="text-xs text-gray-400 mb-1">الوصف</p>
                    <p class="text-sm text-gray-600">{{ $maintenance->description }}</p>
                </div>
                @endif
            </div>

            {{-- Updates --}}
            <div class="card">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">سجل التحديثات</h3>
                </div>
                @if($maintenance->updates->isEmpty())
                <div class="p-8 text-center text-gray-400 text-sm">لا توجد تحديثات بعد</div>
                @else
                <div class="divide-y divide-gray-100">
                    @foreach($maintenance->updates->sortByDesc('created_at') as $update)
                    <div class="p-4 flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 text-sm font-bold">
                            {{ mb_substr($update->user->name ?? '؟', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $update->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $update->created_at->format('Y/m/d H:i') }}
                                @if($update->status_changed_to)
                                · <span class="{{ $statusClass[$update->status_changed_to] ?? '' }}">{{ $statusLabel[$update->status_changed_to] ?? $update->status_changed_to }}</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 mt-1">{{ $update->note }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Images --}}
            @if($maintenance->images && count($maintenance->images))
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">صور المشكلة</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($maintenance->images as $img)
                    <a href="{{ Storage::url($img) }}" target="_blank">
                        <img src="{{ Storage::url($img) }}" alt=""
                             class="w-full h-32 object-cover rounded-xl border border-gray-200 hover:opacity-90 transition">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="card p-5 space-y-3">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">الوحدة</p>
                <p class="font-semibold">
                    <a href="{{ route('units.show', $maintenance->unit) }}" class="text-indigo-600 hover:text-indigo-700">
                        {{ $maintenance->unit->unit_number }}
                    </a>
                </p>
                <p class="text-sm text-gray-500">{{ $maintenance->unit->property->name }}</p>
            </div>

            @if($maintenance->tenant)
            <div class="card p-5 space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">المستأجر</p>
                <p class="font-semibold text-gray-800">{{ $maintenance->tenant->name }}</p>
                <p class="text-sm text-gray-500">{{ $maintenance->tenant->phone }}</p>
            </div>
            @endif

            <div class="card p-5 space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">الفني المُعيَّن</p>
                @if($maintenance->assignedTo)
                    <p class="font-semibold text-gray-800">{{ $maintenance->assignedTo->name }}</p>
                @else
                    <p class="text-gray-400 text-sm">لم يُعيَّن بعد</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal: Update Status --}}
    <div x-show="showStatusModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="showStatusModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">تحديث الحالة</h3>
            <form method="POST" action="{{ route('maintenance.updateStatus', $maintenance) }}" class="space-y-4">
                @csrf @method('POST')
                <div>
                    <label class="form-label">الحالة الجديدة <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="open"        @selected($maintenance->status === 'open')>مفتوح</option>
                        <option value="in_progress" @selected($maintenance->status === 'in_progress')>قيد التنفيذ</option>
                        <option value="resolved"    @selected($maintenance->status === 'resolved')>منتهي</option>
                        <option value="cancelled"   @selected($maintenance->status === 'cancelled')>ملغى</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">ملاحظة</label>
                    <textarea name="note" rows="2" class="form-input" placeholder="اختياري"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showStatusModal = false" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Assign --}}
    <div x-show="showAssignModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="showAssignModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">تعيين فني</h3>
            <form method="POST" action="{{ route('maintenance.assign', $maintenance) }}" class="space-y-4">
                @csrf @method('POST')
                <div>
                    <label class="form-label">الفني <span class="text-red-500">*</span></label>
                    <select name="assigned_to" class="form-input" required>
                        @php $staff = \App\Models\User::role(['admin','manager','staff'])->get(['id','name']); @endphp
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}" @selected($maintenance->assigned_to == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showAssignModal = false" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-primary">تعيين</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
