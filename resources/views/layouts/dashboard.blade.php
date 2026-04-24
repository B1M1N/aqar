<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'عقاري - لوحة التحكم' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f5f6fa; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .bg-dark-blue { background-color: #0f172a; }
        .text-dark-blue { color: #0f172a; }
        .bg-gold { background-color: #b8860b; }
        .text-gold { color: #b8860b; }
        .border-gold { border-color: #b8860b; }
        .hover-bg-gold:hover { background-color: #a0750a; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden flex h-screen" x-data="{ sidebarOpen: false, currentPage: 'overview' }">

    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        {{-- Topbar --}}
        <x-topbar />

        {{-- Page Content --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f5f6fa] p-6">
            {{ $slot }}
        </main>
    </div>

    {{-- Modals --}}
    @stack('modals')

</body>
</html>
