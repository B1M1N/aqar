<x-app-layout>
    <x-slot:title>Find Your Dream Home</x-slot:title>

    {{-- Category Pills --}}
    @php
        $categories = ['All Properties', 'Lands for sale', 'Apartments for rent', 'Commercial', 'Villas', 'Farms'];
    @endphp
    <x-category-bar :categories="$categories" active="All Properties" />

    {{-- Search Bar --}}
    <x-search-bar />

    {{-- Listings Loop --}}
    <div class="mt-8 pb-32">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold tracking-tight">Recent Listings</h2>
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500">
                <span>Sort by:</span>
                <button class="flex items-center gap-1 text-black">
                    Newest
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                </button>
            </div>
        </div>

        @foreach($listings as $listing)
            <x-listing-card :listing="$listing" />
        @endforeach
    </div>
</x-app-layout>
