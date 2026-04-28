@extends('layouts.app')
@section('title', 'الإعدادات')
@section('page-title', 'إعدادات النظام')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">الإعدادات</span>
@endsection

@section('content')
<form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
@csrf @method('POST')

    {{-- General --}}
    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">الإعدادات العامة</h3>
        <div class="max-w-sm">
            <label class="form-label">اسم التطبيق</label>
            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" class="form-input" required>
            @error('app_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Automation --}}
    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">الأتمتة</h3>
        <div class="space-y-4">
            @php
            $toggles = [
                ['auto_invoices',       'إنشاء الفواتير الشهرية تلقائياً',     'يُنشئ النظام فواتير الإيجار في أول يوم من كل شهر'],
                ['late_invoice_updates','تحديث حالة الفواتير المتأخرة تلقائياً','يُعيَّن الفواتير غير المدفوعة بعد تاريخ الاستحقاق كـ "متأخرة"'],
                ['ai_predictions',      'تفعيل التنبؤات الذكية',                'تحسب خوارزميات التنبؤ بالمخاطر والشواغر والصيانة يومياً'],
            ];
            @endphp
            @foreach($toggles as [$key, $label, $hint])
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $hint }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                    <input type="hidden" name="{{ $key }}" value="0">
                    <input type="checkbox" name="{{ $key }}" value="1" class="sr-only peer"
                           {{ old($key, $settings[$key]) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Notifications --}}
    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">الإشعارات</h3>
        <div class="space-y-4">
            @php
            $notifToggles = [
                ['rent_reminders',      'تذكيرات الإيجار'],
                ['lease_expiry_notify', 'تنبيهات انتهاء العقود'],
                ['monthly_reports',     'تقارير شهرية'],
            ];
            @endphp
            @foreach($notifToggles as [$key, $label])
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="{{ $key }}" value="0">
                    <input type="checkbox" name="{{ $key }}" value="1" class="sr-only peer"
                           {{ old($key, $settings[$key]) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
            <div>
                <label class="form-label">أيام التذكير بالإيجار قبل الاستحقاق</label>
                <input type="number" name="rent_reminder_days"
                       value="{{ old('rent_reminder_days', $settings['rent_reminder_days']) }}"
                       min="1" max="30" class="form-input">
                @error('rent_reminder_days')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">أيام التنبيه قبل انتهاء العقد</label>
                <input type="number" name="lease_expiry_days"
                       value="{{ old('lease_expiry_days', $settings['lease_expiry_days']) }}"
                       min="1" max="90" class="form-input">
                @error('lease_expiry_days')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Moyasar Payment --}}
    <div class="card p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-semibold text-gray-800">بوابة الدفع Moyasar</h3>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="moyasar_enabled" value="0">
                <input type="checkbox" name="moyasar_enabled" value="1" class="sr-only peer"
                       {{ old('moyasar_enabled', $settings['moyasar_enabled']) ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="form-label">المفتاح السري (Secret Key)</label>
                <input type="password" name="moyasar_secret_key"
                       value="{{ old('moyasar_secret_key', $settings['moyasar_secret_key']) }}"
                       class="form-input" placeholder="sk_test_...">
            </div>
            <div>
                <label class="form-label">المفتاح العلني (Publishable Key)</label>
                <input type="text" name="moyasar_publishable_key"
                       value="{{ old('moyasar_publishable_key', $settings['moyasar_publishable_key']) }}"
                       class="form-input" placeholder="pk_test_...">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ الإعدادات
        </button>
    </div>

</form>
@endsection
