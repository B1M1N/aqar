{{-- Maintenance Workflow --}}
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">طلبات الصيانة</h2>
        <div class="flex gap-2">
            <div class="bg-white border border-slate-200 rounded-xl p-1 flex text-sm shadow-sm">
                <button class="px-4 py-1.5 bg-[#0f172a] text-white rounded-lg font-medium">الكل</button>
                <button class="px-4 py-1.5 text-slate-500 hover:text-slate-800 rounded-lg font-medium">الجديدة</button>
                <button class="px-4 py-1.5 text-slate-500 hover:text-slate-800 rounded-lg font-medium">قيد التنفيذ</button>
            </div>
            <button class="bg-[#0f172a] text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-slate-800 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                طلب صيانة
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        {{-- Maintenance Card 1 (High Priority) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="flex justify-between items-start mb-3">
                <x-badge type="high">أولوية عالية</x-badge>
                <span class="text-xs text-slate-400 font-mono">#REQ-1042</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">تسرب مياه في الدور الرابع</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1">تسرب مياه من السقف في وحدة B-405، يتطلب تدخل عاجل لمنع الإضرار بالديكورات.</p>
            
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    برج العليا - وحدة B-405
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=Tech+1&background=slate" alt="Tech" class="w-8 h-8 rounded-full ring-2 ring-white">
                        <span class="text-xs font-semibold text-slate-700">فريق السباكة</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">في الطريق</span>
                </div>
            </div>
        </div>

        {{-- Maintenance Card 2 (Medium Priority) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="flex justify-between items-start mb-3">
                <x-badge type="medium">أولوية متوسطة</x-badge>
                <span class="text-xs text-slate-400 font-mono">#REQ-1041</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">عطل في التكييف المركزي</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1">ضعف في التبريد في اللوبي الرئيسي، تم طلب فحص الفريون والمروحة.</p>
            
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    مجمع الياسمين - اللوبي
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full border border-dashed border-slate-300 text-slate-400 flex items-center justify-center text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </div>
                        <span class="text-xs text-slate-500">غير معين</span>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md">قيد المراجعة</span>
                </div>
            </div>
        </div>

        {{-- Maintenance Card 3 (Low Priority) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="flex justify-between items-start mb-3">
                <x-badge type="low">أولوية منخفضة</x-badge>
                <span class="text-xs text-slate-400 font-mono">#REQ-1040</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">تغيير لمبات الإضاءة</h3>
            <p class="text-sm text-slate-500 mb-4 flex-1">احتراق بعض لمبات الإضاءة في الممر الخارجي للفلة.</p>
            
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    فيلا النرجس - الممر
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=Tech+2&background=slate" alt="Tech" class="w-8 h-8 rounded-full ring-2 ring-white">
                        <span class="text-xs font-semibold text-slate-700">فريق الصيانة العامة</span>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">مجدول لغداً</span>
                </div>
            </div>
        </div>
    </div>
</div>
