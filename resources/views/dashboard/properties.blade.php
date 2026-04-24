{{-- Properties Grid --}}
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">إدارة العقارات</h2>
        <button class="bg-[#0f172a] text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-slate-800 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            إضافة عقار
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {{-- Property 1 --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 group-hover:bg-[#b8860b]/10 group-hover:text-[#b8860b] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">برج العليا</h3>
                        <p class="text-xs text-slate-500">الرياض، حي العليا</p>
                    </div>
                </div>
                <x-badge type="active">نشط</x-badge>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-500">معدل الإشغال</span>
                        <span class="font-bold">28/30 وحدة</span>
                    </div>
                    <x-progress-bar percentage="93" colorClass="bg-emerald-500" />
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-sm">
                    <span class="text-slate-500">الإيراد الشهري</span>
                    <span class="font-mono font-bold text-slate-800">SAR 120,000</span>
                </div>
            </div>
        </div>

        {{-- Property 2 --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 group-hover:bg-[#b8860b]/10 group-hover:text-[#b8860b] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 9a2 2 0 1 0 4 0 2 2 0 0 0-4 0"/><path d="M12 2v4"/><path d="M12 12v10"/><path d="M2 12h4"/><path d="M16 12h6"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">مجمع الياسمين</h3>
                        <p class="text-xs text-slate-500">جدة، حي الشاطئ</p>
                    </div>
                </div>
                <x-badge type="maintenance">صيانة</x-badge>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-500">معدل الإشغال</span>
                        <span class="font-bold">15/24 وحدة</span>
                    </div>
                    <x-progress-bar percentage="62" colorClass="bg-amber-500" />
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-sm">
                    <span class="text-slate-500">الإيراد الشهري</span>
                    <span class="font-mono font-bold text-slate-800">SAR 75,000</span>
                </div>
            </div>
        </div>

        {{-- Property 3 --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 group-hover:bg-[#b8860b]/10 group-hover:text-[#b8860b] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">فيلا النرجس</h3>
                        <p class="text-xs text-slate-500">الرياض، حي النرجس</p>
                    </div>
                </div>
                <x-badge type="villa">فيلا خاصة</x-badge>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-500">معدل الإشغال</span>
                        <span class="font-bold">1/1 وحدة</span>
                    </div>
                    <x-progress-bar percentage="100" colorClass="bg-indigo-500" />
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-sm">
                    <span class="text-slate-500">الإيراد السنوي</span>
                    <span class="font-mono font-bold text-slate-800">SAR 280,000</span>
                </div>
            </div>
        </div>
    </div>
</div>
