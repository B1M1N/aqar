@extends('layouts.auth')
@section('title', 'تعيين كلمة مرور جديدة')

@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-1">كلمة مرور جديدة</h2>
<p class="text-sm text-gray-500 mb-6">أدخل كلمة المرور الجديدة لحسابك</p>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input id="email" type="email" name="email" value="{{ old('email', request()->email) }}"
               class="form-input @error('email') border-red-400 @enderror"
               required autocomplete="email">
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="form-label">كلمة المرور الجديدة</label>
        <input id="password" type="password" name="password"
               class="form-input @error('password') border-red-400 @enderror"
               required autocomplete="new-password">
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="form-input" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn-primary w-full justify-center py-3">
        تعيين كلمة المرور
    </button>
</form>
@endsection
