@extends('layouts.auth')
@section('title', 'تسجيل الدخول')

@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-1">أهلاً بك</h2>
<p class="text-sm text-gray-500 mb-6">سجّل دخولك للمتابعة</p>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               class="form-input @error('email') border-red-400 @enderror"
               placeholder="admin@aqari.com" required autofocus autocomplete="email">
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="form-label mb-0">كلمة المرور</label>
            <a href="{{ route('password.request') }}"
               class="text-xs text-indigo-600 hover:text-indigo-700">نسيت كلمة المرور؟</a>
        </div>
        <input id="password" type="password" name="password"
               class="form-input @error('password') border-red-400 @enderror"
               placeholder="••••••••" required autocomplete="current-password">
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-2">
        <input id="remember" type="checkbox" name="remember"
               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="remember" class="text-sm text-gray-600">تذكّرني</label>
    </div>

    <button type="submit" class="btn-primary w-full justify-center py-3">
        تسجيل الدخول
    </button>
</form>
@endsection
