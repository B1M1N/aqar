@props(['categories', 'active' => null])

<div class="w-full overflow-x-auto no-scrollbar py-4">
    <div class="flex items-center gap-3 px-1">
        @foreach($categories as $category)
            <button 
                @class([
                    'px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap',
                    'bg-black text-white shadow-md' => $active === $category,
                    'bg-white text-gray-600 hover:bg-gray-100 border border-gray-100' => $active !== $category,
                ])
            >
                {{ $category }}
            </button>
        @endforeach
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
