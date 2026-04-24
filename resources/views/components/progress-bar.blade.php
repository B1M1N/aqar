@props(['percentage', 'colorClass' => 'bg-blue-500'])

<div class="flex items-center gap-3">
    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ $percentage }}%"></div>
    </div>
    <span class="text-sm font-medium text-slate-600 font-mono w-9">{{ $percentage }}%</span>
</div>
