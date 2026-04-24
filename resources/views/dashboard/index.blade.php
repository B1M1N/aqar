<x-dashboard-layout>
    <!-- Page switching logic via Alpine.js -->
    <div x-show="currentPage === 'overview'" x-transition.opacity.duration.300ms>
        
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-stat-card 
                title="إجمالي الإيرادات" 
                value="SAR 2.4M" 
                icon='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>' 
                trend="12.5%" 
                trendType="up" 
                iconBg="bg-emerald-50" 
                iconColor="text-emerald-600" 
            />
            <x-stat-card 
                title="العقارات النشطة" 
                value="142" 
                icon='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>' 
                trend="3" 
                trendType="up" 
                iconBg="bg-[#b8860b]/10" 
                iconColor="text-[#b8860b]" 
            />
            <x-stat-card 
                title="معدل الإشغال" 
                value="94%" 
                icon='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' 
                trend="2.1%" 
                trendType="up" 
                iconBg="bg-blue-50" 
                iconColor="text-blue-600" 
            />
            <x-stat-card 
                title="طلبات الصيانة" 
                value="18" 
                icon='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><mpath d="m14.7 13.5 3.3-3.3a2.12 2.12 0 0 0-3-3l-3.3 3.3"/><path d="m10.7 13.5-3.3 3.3a2.12 2.12 0 0 0 3 3l3.3-3.3"/><path d="m16.5 10.5 1.5-1.5"/><path d="m10.5 16.5-1.5 1.5"/></svg>' 
                trend="5" 
                trendType="down" 
                iconBg="bg-amber-50" 
                iconColor="text-amber-600" 
            />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Main Content Column --}}
            <div class="xl:col-span-2 space-y-8">
                
                {{-- AI Insights Engine --}}
                <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">تحليلات الذكاء الاصطناعي</h2>
                            <p class="text-sm text-slate-500">توصيات مبنية على بيانات عقاراتك</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Payment Default Risk --}}
                        <div class="bg-red-50/50 rounded-xl p-4 border border-red-100 hover:bg-red-50 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-red-800">مخاطر السداد</span>
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full font-mono">82%</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">الوحدة B-402 (شركة الأفق) لديها احتمالية تأخر في السداد بناءً على النمط التاريخي.</p>
                            <button class="text-sm font-semibold text-red-600 hover:text-red-700">إرسال تذكير استباقي &larr;</button>
                        </div>

                        {{-- Occupancy Forecasting --}}
                        <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100 hover:bg-amber-50 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-amber-800">توقعات الإشغال</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">3 عقود تنتهي خلال 60 يوماً في برج النخيل.</p>
                            <button class="text-sm font-semibold text-amber-600 hover:text-amber-700">بدء حملة التسويق &larr;</button>
                        </div>

                        {{-- Preventive Maintenance --}}
                        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 hover:bg-blue-50 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-blue-800">صيانة وقائية</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">يوصى بفحص نظام التكييف المركزي في المجمع السكني (العمر: 5 سنوات).</p>
                            <button class="text-sm font-semibold text-blue-600 hover:text-blue-700">جدولة فحص &larr;</button>
                        </div>
                    </div>
                </section>

                {{-- Invoices Table --}}
                <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800">أحدث الفواتير</h2>
                        <a href="#" class="text-sm font-semibold text-[#b8860b] hover:text-[#a0750a]">عرض الكل</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">رقم الفاتورة</th>
                                    <th class="px-6 py-4 font-semibold">المستأجر</th>
                                    <th class="px-6 py-4 font-semibold">العقار / الوحدة</th>
                                    <th class="px-6 py-4 font-semibold">المبلغ</th>
                                    <th class="px-6 py-4 font-semibold">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-medium">#INV-2023-089</td>
                                    <td class="px-6 py-4 font-semibold">شركة مسارات التقنية</td>
                                    <td class="px-6 py-4 text-slate-500">برج العليا - A20</td>
                                    <td class="px-6 py-4 font-mono font-medium">SAR 45,000</td>
                                    <td class="px-6 py-4"><x-badge type="late">متأخر</x-badge></td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-medium">#INV-2023-090</td>
                                    <td class="px-6 py-4 font-semibold">خالد عبدالله</td>
                                    <td class="px-6 py-4 text-slate-500">مجمع الياسمين - V4</td>
                                    <td class="px-6 py-4 font-mono font-medium">SAR 12,500</td>
                                    <td class="px-6 py-4"><x-badge type="paid">مدفوع</x-badge></td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-medium">#INV-2023-091</td>
                                    <td class="px-6 py-4 font-semibold">مؤسسة البناء</td>
                                    <td class="px-6 py-4 text-slate-500">مستودعات السلي - W1</td>
                                    <td class="px-6 py-4 font-mono font-medium">SAR 85,000</td>
                                    <td class="px-6 py-4"><x-badge type="pending">قيد الانتظار</x-badge></td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-medium">#INV-2023-092</td>
                                    <td class="px-6 py-4 font-semibold">سارة محمد</td>
                                    <td class="px-6 py-4 text-slate-500">شقق النرجس - 105</td>
                                    <td class="px-6 py-4 font-mono font-medium">SAR 4,200</td>
                                    <td class="px-6 py-4"><x-badge type="draft">مسودة</x-badge></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>

            {{-- Sidebar Column (Revenue Chart) --}}
            <div class="space-y-8">
                <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-6">الإيرادات (2024)</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">الربع الأول</span>
                                <span class="font-mono font-medium text-slate-800">SAR 450K</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="bg-[#0f172a] h-2.5 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">الربع الثاني</span>
                                <span class="font-mono font-medium text-slate-800">SAR 520K</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="bg-[#b8860b] h-2.5 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">الربع الثالث</span>
                                <span class="font-mono font-medium text-slate-800">SAR 380K</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">الربع الرابع (متوقع)</span>
                                <span class="font-mono font-medium text-slate-800">SAR 600K</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="bg-slate-300 h-2.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Additional Pages Placeholders (handled via x-show) -->
    <div x-show="currentPage === 'properties'" x-transition.opacity.duration.300ms style="display: none;">
        @include('dashboard.properties')
    </div>
    <div x-show="currentPage === 'maintenance'" x-transition.opacity.duration.300ms style="display: none;">
        @include('dashboard.maintenance')
    </div>
</x-dashboard-layout>
