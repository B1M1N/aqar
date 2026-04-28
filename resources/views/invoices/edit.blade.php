@extends('layouts.app')
@section('title', 'تعديل فاتورة')
@section('page-title', 'تعديل — ' . $invoice->invoice_number)
@section('breadcrumb')
    <a href="{{ route('invoices.index') }}" class="hover:text-gray-700">الفواتير</a>
    <span class="mx-1">/</span>
    <a href="{{ route('invoices.show', $invoice) }}" class="hover:text-gray-700">{{ $invoice->invoice_number }}</a>
    <span class="mx-1">/</span><span class="text-gray-700">تعديل</span>
@endsection

@section('content')
<form method="POST" action="{{ route('invoices.update', $invoice) }}" class="space-y-6">
@csrf @method('PUT')

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">بيانات الفاتورة</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">العقد <span class="text-red-500">*</span></label>
                <select name="lease_id" class="form-input" required>
                    @foreach($leases as $lease)
                        <option value="{{ $lease->id }}"
                            @selected(old('lease_id',$invoice->lease_id) == $lease->id)>
                            {{ $lease->tenant->name }} — {{ $lease->unit->property->name }} / {{ $lease->unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('lease_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المستأجر <span class="text-red-500">*</span></label>
                <select name="tenant_id" class="form-input" required>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}"
                            @selected(old('tenant_id',$invoice->tenant_id) == $tenant->id)>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الوحدة <span class="text-red-500">*</span></label>
                <select name="unit_id" class="form-input" required>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}"
                            @selected(old('unit_id',$invoice->unit_id) == $unit->id)>
                            {{ $unit->property->name }} — {{ $unit->unit_number }}
                        </option>
                    @endforeach
                </select>
                @error('unit_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المبلغ (ر.س) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" value="{{ old('amount', $invoice->amount) }}"
                       min="0" step="0.01" class="form-input" required>
                @error('amount')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                <input type="date" name="due_date"
                       value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                       class="form-input" required>
                @error('due_date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">نوع الفاتورة <span class="text-red-500">*</span></label>
                <select name="type" class="form-input" required>
                    <option value="rent"        @selected(old('type',$invoice->type) === 'rent')>إيجار</option>
                    <option value="maintenance" @selected(old('type',$invoice->type) === 'maintenance')>صيانة</option>
                    <option value="utility"     @selected(old('type',$invoice->type) === 'utility')>خدمات</option>
                    <option value="other"       @selected(old('type',$invoice->type) === 'other')>أخرى</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="pending"   @selected(old('status',$invoice->status) === 'pending')>غير مدفوع</option>
                    <option value="draft"     @selected(old('status',$invoice->status) === 'draft')>مسودة</option>
                    <option value="paid"      @selected(old('status',$invoice->status) === 'paid')>مدفوع</option>
                    <option value="late"      @selected(old('status',$invoice->status) === 'late')>متأخر</option>
                    <option value="cancelled" @selected(old('status',$invoice->status) === 'cancelled')>ملغى</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" rows="2" class="form-input">{{ old('notes', $invoice->notes) }}</textarea>
            @error('notes')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('invoices.show', $invoice) }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ التعديلات
        </button>
    </div>

</form>
@endsection
