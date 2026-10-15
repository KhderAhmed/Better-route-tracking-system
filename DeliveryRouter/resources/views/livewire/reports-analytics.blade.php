<div class="bg-slate-950 text-slate-100 min-h-screen pb-12 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950" dir="rtl">
    
    <!-- شريط التنقل العلوي -->
    @livewire('navbar')

    <div class="max-w-7xl mx-auto px-6 space-y-6 mt-6">

        <!-- الهيدر والتحكم بالفترة الزمنية -->
        <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl backdrop-blur-xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white flex items-center gap-2">
                    <span>📊</span> تقارير الأداء والتحليلات التشغيلية
                </h1>
                <p class="text-xs text-slate-400 mt-1">مؤشرات مسافات الشحن، تكاليف التشغيل التقديرية، وإنجازات الأسطول</p>
            </div>

            <div class="flex items-center gap-3">
                <select wire:model.live="dateRange" class="bg-slate-950/80 border border-slate-800 rounded-2xl px-4 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-emerald-500">
                    <option value="all">كل الأوقات</option>
                    <option value="today">اليوم</option>
                    <option value="month">هذا الشهر</option>
                </select>
                <button onclick="window.print()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-2xl transition shadow">
                    🖨️ طباعة التقرير
                </button>
            </div>
        </div>

        <!-- بطاقات مؤشرات الأداء الكبرى (KPIs) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-slate-900/60 border border-slate-800/80 p-5 rounded-3xl shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400">إجمالي المسافات المقطوعة</span>
                    <h2 class="text-3xl font-black text-emerald-400 mt-1">{{ round($totalKm, 1) }} <span class="text-xs font-normal text-slate-400">كم</span></h2>
                </div>
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-2xl">
                    🛣️
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 p-5 rounded-3xl shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400">الطلبات المُسلمة بنجاح</span>
                    <h2 class="text-3xl font-black text-sky-400 mt-1">{{ $totalDelivered }}</h2>
                </div>
                <div class="p-4 bg-sky-500/10 border border-sky-500/20 text-sky-400 rounded-2xl text-2xl">
                    📦
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 p-5 rounded-3xl shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400">المسارات المكتملة</span>
                    <h2 class="text-3xl font-black text-amber-400 mt-1">{{ $completedRoutes }}</h2>
                </div>
                <div class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-2xl text-2xl">
                    ✅
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800/80 p-5 rounded-3xl shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400">الأسطول النشط ميدانياً</span>
                    <h2 class="text-3xl font-black text-purple-400 mt-1">{{ $activeFleetCount }}</h2>
                </div>
                <div class="p-4 bg-purple-500/10 border border-purple-500/20 text-purple-400 rounded-2xl text-2xl">
                    🚛
                </div>
            </div>

        </div>

        <!-- جدول أحدث المسارات المسجلة في التقارير -->
        <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl space-y-4">
            <h2 class="text-sm font-bold text-white">سجل المسارات والتحليلات الأخيرة</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right text-slate-300">
                    <thead class="bg-slate-950/60 text-slate-400 uppercase border-b border-slate-800">
                        <tr>
                            <th class="p-3">رقم المسار</th>
                            <th class="p-3">الشاحنة المعينة</th>
                            <th class="p-3">المستودع المنطلق منه</th>
                            <th class="p-3">المسافة التقديرية</th>
                            <th class="p-3">الحالة التشغيلية</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($recentRoutes as $route)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="p-3 font-bold text-white">#{{ $route->id }}</td>
                                <td class="p-3 text-slate-300">{{ $route->vehicle?->plate_number ?? 'غير متوفر' }}</td>
                                <td class="p-3 text-slate-400">{{ $route->depot?->name ?? 'المستودع الرئيسي' }}</td>
                                <td class="p-3 text-emerald-400 font-semibold">{{ $route->total_distance_km }} كم</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 text-[10px] rounded-full font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                        {{ $route->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 italic">لا توجد مسارات مسجلة في التقارير حالياً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>