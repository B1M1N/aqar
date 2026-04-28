@extends('layouts.app')
@section('title', 'تعديل العقد')
@section('page-title', 'تعديل عقد الإيجار')
@section('breadcrumb')
    <a href="{{ route('leases.index') }}" class="hover:text-gray-700">عقود الإيجار</a>
    <span class="mx-1">/</span>
    <a href="{{ route('leases.show', $lease) }}" class="hover:text-gray-700">#{{ $lease->id }}</a>
    <span class="mx-1">/</span><span class="text-gray-700">تعديل</span>
@endsection

@section('content')
<form method="POST" action="{{ route('leases.update', $lease) }}" class="space-y-6">
@csrf @method('PUT')

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">أطراف العقد</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">الوحدة <span class="text-red-500">*</span></label>
                <select name="unit_id" class="form-input" required>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}"
                            @selected(old('unit_id', $lease->unit_id) == $unit->id)>
                            {{ $unit->property->name }} — {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المستأجر <span class="text-red-500">*</span></label>
                <select name="tenant_id" class="form-input" required>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}"
                            @selected(old('tenant_id', $lease->tenant_id) == $tenant->id)>
                            {{ $tenant->name }} ({{ $tenant->phone }})
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">تفاصيل العقد</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">تاريخ البداية <span class="text-red-500">*</span></label>
                <input type="date" name="start_date"
                       value="{{ old('start_date', $lease->start_date->format('Y-m-d')) }}"
                       class="form-input" required>
                @error('start_date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">تاريخ الانتهاء <span class="text-red-500">*</span></label>
                <input type="date" name="end_date"
                       value="{{ old('end_date', $lease->end_date->format('Y-m-d')) }}"
                       class="form-input" required>
                @error('end_date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">مبلغ الإيجار (ر.س) <span class="text-red-500">*</span></label>
                <input type="number" name="rent_amount"
                       value="{{ old('rent_amount', $lease->rent_amount) }}"
                       min="0" step="0.01" class="form-input" required>
                @error('rent_amount')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">مبلغ التأمين (ر.س)</label>
                <input type="number" name="deposit_amount"
                       value="{{ old('deposit_amount', $lease->deposit_amount) }}"
                       min="0" step="0.01" class="form-input">
                @error('deposit_amount')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">يوم السداد الشهري <span class="text-red-500">*</span></label>
                <input type="number" name="payment_day"
                       value="{{ old('payment_day', $lease->payment_day) }}"
                       min="1" max="28" class="form-input" required>
                @error('payment_day')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="active"     @selected(old('status',$lease->status) === 'active')>نشط</option>
                    <option value="pending"    @selected(old('status',$lease->status) === 'pending')>معلق</option>
                    <option value="expired"    @selected(old('status',$lease->status) === 'expired')>منتهي</option>
                    <option value="terminated" @selected(old('status',$lease->status) === 'terminated')>مُنهى</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">ملاحظات</h3>
        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $lease->notes) }}</textarea>
        @error('notes')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('leases.show', $lease) }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ التعديلات
        </button>
    </div>

</form>
@endsection
