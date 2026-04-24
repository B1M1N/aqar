<div class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" x-cloak></div>

<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" class="fixed inset-y-0 right-0 z-50 w-64 bg-[#0f172a] text-white transition-transform duration-300 ease-in-out lg:static lg:inset-0 shadow-xl lg:shadow-none flex flex-col">
    <div class="flex items-center justify-between h-20 px-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#b8860b] flex items-center justify-center shadow-lg shadow-[#b8860b]/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <span class="text-2xl font-bold tracking-wider">عقاري</span>
        </div>
        <button class="lg:hidden text-white/70 hover:text-white" @click="sidebarOpen = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="#" @click.prevent="currentPage = 'overview'" :class="currentPage === 'overview' ? 'bg-[#b8860b] text-white shadow-md shadow-[#b8860b]/20' : 'text-slate-300 hover:bg-white/5 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span class="font-medium">لوحة القيادة</span>
        </a>
        <a href="#" @click.prevent="currentPage = 'properties'" :class="currentPage === 'properties' ? 'bg-[#b8860b] text-white shadow-md shadow-[#b8860b]/20' : 'text-slate-300 hover:bg-white/5 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
            <span class="font-medium">العقارات</span>
        </a>
        <a href="#" @click.prevent="currentPage = 'maintenance'" :class="currentPage === 'maintenance' ? 'bg-[#b8860b] text-white shadow-md shadow-[#b8860b]/20' : 'text-slate-300 hover:bg-white/5 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m14.7 13.5 3.3-3.3a2.12 2.12 0 0 0-3-3l-3.3 3.3"/><path d="m10.7 13.5-3.3 3.3a2.12 2.12 0 0 0 3 3l3.3-3.3"/><path d="m16.5 10.5 1.5-1.5"/><path d="m10.5 16.5-1.5 1.5"/></svg>
            <span class="font-medium">الصيانة</span>
        </a>
        <a href="#" @click.prevent="currentPage = 'finance'" :class="currentPage === 'finance' ? 'bg-[#b8860b] text-white shadow-md shadow-[#b8860b]/20' : 'text-slate-300 hover:bg-white/5 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            <span class="font-medium">المالية</span>
        </a>
        <a href="#" @click.prevent="currentPage = 'ai-insights'" :class="currentPage === 'ai-insights' ? 'bg-[#b8860b] text-white shadow-md shadow-[#b8860b]/20' : 'text-slate-300 hover:bg-white/5 hover:text-white'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            <span class="font-medium flex-1">تحليلات الذكاء الاصطناعي</span>
            <span class="bg-[#b8860b]/20 text-[#b8860b] py-0.5 px-2 rounded-full text-[10px] font-bold">ميزة جديدة</span>
        </a>
    </nav>
    
    <div class="p-6 mt-auto border-t border-white/10">
        <div class="flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name=Admin&background=b8860b&color=fff" alt="User" class="w-10 h-10 rounded-full border-2 border-[#0f172a] ring-2 ring-white/10">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">أحمد مدير النظام</p>
                <p class="text-xs text-slate-400 truncate">admin@aaqari.com</p>
            </div>
        </div>
    </div>
</aside>
