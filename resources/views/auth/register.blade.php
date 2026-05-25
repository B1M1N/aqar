@extends('layouts.auth')
@section('title', 'إنشاء حساب')

@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-1">إنشاء حساب جديد</h2>
<p class="text-sm text-gray-500 mb-6">أنشئ حسابك للبدء في استخدام النظام</p>

@if ($errors->any())
    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        يرجى تصحيح الأخطاء في النموذج ثم المحاولة مرة أخرى.
    </div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ showPassword: false, showConfirmPassword: false, loading: false }" @submit="loading = true">
    @csrf

    <div>
        <label for="name" class="form-label">الاسم الكامل</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}"
               class="form-input @error('name') border-red-400 @enderror"
               placeholder="الاسم الكامل" required autocomplete="name">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               class="form-input @error('email') border-red-400 @enderror"
               placeholder="name@example.com" required autocomplete="email">
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="form-label">رقم الجوال (اختياري)</label>
        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
               class="form-input @error('phone') border-red-400 @enderror"
               placeholder="05xxxxxxxx" autocomplete="tel">
        @error('phone')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="form-label">كلمة المرور</label>
        <div class="relative">
            <input id="password" x-bind:type="showPassword ? 'text' : 'password'" name="password"
                   class="form-input pe-24 @error('password') border-red-400 @enderror"
                   placeholder="••••••••" required autocomplete="new-password">
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 start-3 text-xs text-indigo-600 hover:text-indigo-700 transition">
                <span x-text="showPassword ? 'إخفاء' : 'إظهار'"></span>
            </button>
        </div>
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
        <div class="relative">
            <input id="password_confirmation" x-bind:type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation"
                   class="form-input pe-24"
                   placeholder="••••••••" required autocomplete="new-password">
            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 start-3 text-xs text-indigo-600 hover:text-indigo-700 transition">
                <span x-text="showConfirmPassword ? 'إخفاء' : 'إظهار'"></span>
            </button>
        </div>
    </div>

    <p class="text-xs text-gray-500">يجب أن تتكون كلمة المرور من 8 أحرف على الأقل وتحتوي على أحرف كبيرة وصغيرة وأرقام ورموز.</p>

    <button type="submit" class="btn-primary w-full justify-center py-3 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
            x-bind:disabled="loading"
            x-bind:class="loading ? 'scale-[0.99]' : 'scale-100'">
        <span x-show="!loading">إنشاء الحساب</span>
        <span x-show="loading" class="inline-flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-90" fill="currentColor" d="M22 12a10 10 0 00-10-10v4a6 6 0 016 6h4z"></path>
            </svg>
            جاري إنشاء الحساب...
        </span>
    </button>

    <p class="text-sm text-gray-600 text-center">
        لديك حساب بالفعل؟
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition">العودة لتسجيل الدخول</a>
    </p>
</form>
@endsection
