@extends('layouts.app')
@section('title', 'تعديل مستأجر')
@section('page-title', 'تعديل — ' . $tenant->name)
@section('breadcrumb')
    <a href="{{ route('tenants.index') }}" class="hover:text-gray-700">المستأجرون</a>
    <span class="mx-1">/</span>
    <a href="{{ route('tenants.show', $tenant) }}" class="hover:text-gray-700">{{ $tenant->name }}</a>
    <span class="mx-1">/</span><span class="text-gray-700">تعديل</span>
@endsection

@section('content')
<form method="POST" action="{{ route('tenants.update', $tenant) }}" class="space-y-6">
@csrf @method('PUT')

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">البيانات الشخصية</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">الاسم الكامل <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="form-input" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">البريد الإلكتروني <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="form-input" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">رقم الهاتف <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="form-input" required>
                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الجنسية</label>
                <input type="text" name="nationality" value="{{ old('nationality', $tenant->nationality) }}" class="form-input">
                @error('nationality')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">بيانات الهوية</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">نوع الهوية <span class="text-red-500">*</span></label>
                <select name="id_type" class="form-input" required>
                    <option value="national_id" @selected(old('id_type',$tenant->id_type) === 'national_id')>هوية وطنية</option>
                    <option value="passport"    @selected(old('id_type',$tenant->id_type) === 'passport')>جواز سفر</option>
                    <option value="iqama"       @selected(old('id_type',$tenant->id_type) === 'iqama')>إقامة</option>
                </select>
                @error('id_type')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">رقم الهوية <span class="text-red-500">*</span></label>
                <input type="text" name="national_id"
                       value="{{ old('national_id', $tenant->national_id) }}" class="form-input" required>
                @error('national_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">تاريخ انتهاء الهوية</label>
                <input type="date" name="id_expiry"
                       value="{{ old('id_expiry', $tenant->id_expiry?->format('Y-m-d')) }}" class="form-input">
                @error('id_expiry')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">جهة اتصال للطوارئ</label>
                <input type="text" name="emergency_contact"
                       value="{{ old('emergency_contact', $tenant->emergency_contact) }}" class="form-input">
                @error('emergency_contact')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">ملاحظات</h3>
        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $tenant->notes) }}</textarea>
        @error('notes')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('tenants.show', $tenant) }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ التعديلات
        </button>
    </div>

</form>
@endsection
