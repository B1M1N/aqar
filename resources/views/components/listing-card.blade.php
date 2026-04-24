@props(['listing'])

<div class="group bg-white rounded-2xl p-4 shadow-sm border border-gray-50 hover:shadow-md transition-all duration-300 cursor-pointer mb-4">
    <div class="flex gap-4 items-start">
        {{-- Thumbnail Image --}}
        <div class="relative w-32 h-32 md:w-40 md:h-40 shrink-0 overflow-hidden rounded-2xl bg-gray-100">
            @if($listing['image'])
                <img src="{{ $listing['image'] }}" alt="{{ $listing['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                </div>
            @endif

            {{-- Price Tag Overlay (Modern Apple style) --}}
            <div class="absolute bottom-2 left-2 right-2">
                <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-sm inline-block">
                    <span class="text-xs font-bold text-gray-900">{{ $listing['price'] }}</span>
                </div>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 flex flex-col min-w-0 py-1">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-900 mb-2 truncate group-hover:text-black leading-tight">
                    {{ $listing['title'] }}
                </h3>
                <button class="text-gray-300 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </button>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $listing['city'] }}
                </span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $listing['time_ago'] }}
                </span>
            </div>

            {{-- User Info (Haraj style) --}}
            <div class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-100 overflow-hidden ring-2 ring-white">
                        @if($listing['user']['avatar'])
                            <img src="{{ $listing['user']['avatar'] }}" alt="{{ $listing['user']['name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500 text-xs font-medium">
                                {{ strtoupper(substr($listing['user']['name'], 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-900 leading-none mb-0.5">{{ $listing['user']['name'] }}</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Verified Pro</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="p-2 rounded-xl bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </button>
                    <button class="p-2 rounded-xl bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 8"/><path d="M14.05 6A5 5 0 0 1 18 10"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
