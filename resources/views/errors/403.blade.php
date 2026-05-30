<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-600">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير مصرح — عقاري</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur rounded-2xl mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="text-6xl font-bold text-white mb-2">403</h1>
        <h2 class="text-xl font-semibold text-white mb-2">غير مصرح بالوصول</h2>
        <p class="text-indigo-200 mb-8">ليس لديك الصلاحية للوصول إلى هذه الصفحة.</p>

        <div class="flex items-center justify-center gap-3">
            @auth
                @if(auth()->user()->hasRole('user'))
                <a href="{{ route('properties.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    العودة إلى العقارات
                </a>
                @else
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    العودة للوحة التحكم
                </a>
                @endif
            @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow hover:bg-indigo-50 transition">
                تسجيل الدخول
            </a>
            @endauth
        </div>
    </div>

</body>
</html>
