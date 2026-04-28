@extends('layouts.app')
@section('title', 'طلب صيانة جديد')
@section('page-title', 'طلب صيانة جديد')
@section('breadcrumb')
    <a href="{{ route('maintenance.index') }}" class="hover:text-gray-700">الصيانة</a>
    <span class="mx-1">/</span><span class="text-gray-700">طلب جديد</span>
@endsection

@section('content')
<form method="POST" action="{{ route('maintenance.store') }}" enctype="multipart/form-data" class="space-y-6">
@csrf

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">تفاصيل الطلب</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="form-label">عنوان الطلب <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input" required
                       placeholder="مثال: تسرب مياه في الحمام">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الوحدة <span class="text-red-500">*</span></label>
                <select name="unit_id" class="form-input" required>
                    <option value="">اختر الوحدة</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}"
                            @selected(old('unit_id', $selectedUnit?->id) == $unit->id)>
                            {{ $unit->property->name }} — {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المستأجر</label>
                <select name="tenant_id" class="form-input">
                    <option value="">اختر المستأجر (اختياري)</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" @selected(old('tenant_id') == $tenant->id)>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">نوع العطل <span class="text-red-500">*</span></label>
                <select name="type" class="form-input" required>
                    <option value="electrical"  @selected(old('type') === 'electrical')>كهرباء</option>
                    <option value="plumbing"    @selected(old('type') === 'plumbing')>سباكة</option>
                    <option value="hvac"        @selected(old('type') === 'hvac')>تكييف</option>
                    <option value="structural"  @selected(old('type') === 'structural')>هيكلي</option>
                    <option value="appliance"   @selected(old('type') === 'appliance')>أجهزة</option>
                    <option value="other"       @selected(old('type','other') === 'other')>أخرى</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الأولوية <span class="text-red-500">*</span></label>
                <select name="priority" class="form-input" required>
                    <option value="low"    @selected(old('priority') === 'low')>منخفض</option>
                    <option value="medium" @selected(old('priority','medium') === 'medium')>متوسط</option>
                    <option value="high"   @selected(old('priority') === 'high')>عالي</option>
                    <option value="urgent" @selected(old('priority') === 'urgent')>عاجل</option>
                </select>
                @error('priority')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="open"        @selected(old('status','open') === 'open')>مفتوح</option>
                    <option value="in_progress" @selected(old('status') === 'in_progress')>قيد التنفيذ</option>
                    <option value="resolved"    @selected(old('status') === 'resolved')>منتهي</option>
                    <option value="cancelled"   @selected(old('status') === 'cancelled')>ملغى</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الفني المُعيَّن</label>
                <select name="assigned_to" class="form-input">
                    <option value="">اختر الفني (اختياري)</option>
                    @foreach($staff as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">التاريخ المقرر</label>
                <input type="datetime-local" name="scheduled_at"
                       value="{{ old('scheduled_at') }}" class="form-input">
                @error('scheduled_at')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">التكلفة المتوقعة (ر.س)</label>
                <input type="number" name="cost" value="{{ old('cost') }}"
                       min="0" step="0.01" class="form-input" placeholder="0.00">
                @error('cost')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="form-label">الوصف</label>
                <textarea name="description" rows="3" class="form-input"
                          placeholder="تفاصيل إضافية عن المشكلة...">{{ old('description') }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">صور المشكلة</h3>
        <input type="file" name="images[]" accept="image/*" multiple class="form-input">
        <p class="text-xs text-gray-400">يمكن رفع حتى 10 صور</p>
        @error('images.*')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('maintenance.index') }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            إنشاء الطلب
        </button>
    </div>

</form>
@endsection
