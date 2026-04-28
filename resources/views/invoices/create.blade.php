@extends('layouts.app')
@section('title', 'فاتورة جديدة')
@section('page-title', 'إنشاء فاتورة جديدة')
@section('breadcrumb')
    <a href="{{ route('invoices.index') }}" class="hover:text-gray-700">الفواتير</a>
    <span class="mx-1">/</span><span class="text-gray-700">فاتورة جديدة</span>
@endsection

@section('content')
<form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
@csrf

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">بيانات الفاتورة</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">العقد <span class="text-red-500">*</span></label>
                <select name="lease_id" id="lease_id" class="form-input" required
                        x-data
                        @change="
                            const opt = $event.target.selectedOptions[0];
                            document.getElementById('tenant_id').value = opt.dataset.tenant || '';
                            document.getElementById('unit_id').value = opt.dataset.unit || '';
                            document.getElementById('rent_amount').value = opt.dataset.rent || '';
                        ">
                    <option value="">اختر العقد</option>
                    @foreach($leases as $lease)
                        <option value="{{ $lease->id }}"
                            data-tenant="{{ $lease->tenant_id }}"
                            data-unit="{{ $lease->unit_id }}"
                            data-rent="{{ $lease->rent_amount }}"
                            @selected(old('lease_id', $selectedLease?->id) == $lease->id)>
                            {{ $lease->tenant->name }} — {{ $lease->unit->property->name }} / {{ $lease->unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('lease_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المستأجر <span class="text-red-500">*</span></label>
                <select name="tenant_id" id="tenant_id" class="form-input" required>
                    <option value="">اختر المستأجر</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}"
                            @selected(old('tenant_id', $selectedLease?->tenant_id) == $tenant->id)>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الوحدة <span class="text-red-500">*</span></label>
                <select name="unit_id" id="unit_id" class="form-input" required>
                    <option value="">اختر الوحدة</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}"
                            @selected(old('unit_id', $selectedLease?->unit_id) == $unit->id)>
                            {{ $unit->property->name }} — {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المبلغ (ر.س) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" id="rent_amount"
                       value="{{ old('amount', $selectedLease?->rent_amount) }}"
                       min="0" step="0.01" class="form-input" required>
                @error('amount')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-input" required>
                @error('due_date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">نوع الفاتورة <span class="text-red-500">*</span></label>
                <select name="type" class="form-input" required>
                    <option value="rent"        @selected(old('type','rent') === 'rent')>إيجار</option>
                    <option value="maintenance" @selected(old('type') === 'maintenance')>صيانة</option>
                    <option value="utility"     @selected(old('type') === 'utility')>خدمات</option>
                    <option value="other"       @selected(old('type') === 'other')>أخرى</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="pending" @selected(old('status','pending') === 'pending')>غير مدفوع</option>
                    <option value="draft"   @selected(old('status') === 'draft')>مسودة</option>
                    <option value="paid"    @selected(old('status') === 'paid')>مدفوع</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
            @error('notes')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('invoices.index') }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            إنشاء الفاتورة
        </button>
    </div>

</form>
@endsection
