@extends('layouts.auth')
@section('title', 'نسيت كلمة المرور')

@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-1">إعادة تعيين كلمة المرور</h2>
<p class="text-sm text-gray-500 mb-6">أدخل بريدك الإلكتروني وسنرسل لك رابط الاسترداد</p>

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf

    <div>
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               class="form-input @error('email') border-red-400 @enderror"
               required autofocus autocomplete="email">
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn-primary w-full justify-center py-3">
        إرسال رابط الاسترداد
    </button>

    <p class="text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700">العودة لتسجيل الدخول</a>
    </p>
</form>
@endsection
