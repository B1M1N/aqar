@props(['type' => 'default'])

@php
    $classes = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
        'maintenance' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
        'hotel' => 'bg-purple-50 text-purple-700 ring-1 ring-purple-600/20',
        'villa' => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-600/20',
        'high' => 'bg-red-50 text-red-700 ring-1 ring-red-600/20',
        'medium' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
        'low' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20',
        'paid' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
        'late' => 'bg-red-50 text-red-700 ring-1 ring-red-600/20',
        'pending' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
        'draft' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-600/20',
        'default' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-600/20',
    ];
    $activeClass = $classes[$type] ?? $classes['default'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $activeClass }}">
    {{ $slot }}
</span>
