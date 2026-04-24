@props(['title', 'value', 'icon', 'trend' => null, 'trendType' => 'up', 'iconBg' => 'bg-blue-50', 'iconColor' => 'text-blue-600'])

<div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-slate-800 font-mono">{{ $value }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl {{ $iconBg }} {{ $iconColor }} flex items-center justify-center">
            {!! $icon !!}
        </div>
    </div>
    
    @if($trend)
        <div class="flex items-center gap-2 text-sm">
            <span class="flex items-center gap-1 font-medium {{ $trendType === 'up' ? 'text-emerald-600' : 'text-red-600' }}">
                @if($trendType === 'up')
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
                @endif
                {{ $trend }}
            </span>
            <span class="text-slate-400">مقارنة بالشهر الماضي</span>
        </div>
    @endif
</div>
