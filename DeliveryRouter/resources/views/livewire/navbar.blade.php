<nav class="bg-slate-900/85 border-b border-slate-800 backdrop-blur-xl sticky top-0 z-50 px-6 py-4 shadow-2xl" dir="rtl">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        
        <!-- شعار النظام -->
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-tr from-emerald-500 to-sky-500 rounded-xl shadow-lg shadow-emerald-500/20">
                <span class="text-xl">🚀</span>
            </div>
            <div>
                <span class="font-extrabold text-lg bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                    نظام إدارة التوصيل والأسطول
                </span>
                <p class="text-[10px] text-slate-400 font-medium">لوحة التحكم المركزية</p>
            </div>
        </div>

        <!-- روابط التنقل السريعة -->
        <div class="hidden xl:flex items-center gap-1 text-xs font-semibold bg-slate-950/60 p-1.5 rounded-2xl border border-slate-800/80">
            <a href="{{ url('/') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">📊 الداشبورد</a>
            <a href="{{ route('chart.manager') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('chart.manager') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">📈 الرسوم البيانية</a>
            <a href="{{ route('delivery.dashboard') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('delivery.dashboard') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">📦 الطلبات</a>
            <a href="{{ route('route.planner') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('route.planner') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">🗺️ المسارات</a>
            <a href="{{ route('vehicle.manager') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('vehicle.manager') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">🚛 الأسطول</a>
            <a href="{{ route('depot.manager') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('depot.manager') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">🏭 المستودعات</a>
            <a href="{{ route('user.manager') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('user.manager') ? 'bg-gradient-to-r from-emerald-500/20 to-sky-500/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">👥 المستخدمين</a>
        </div>

        <!-- معلومات الأدمن وزر الخروج -->
        <div class="flex items-center gap-4">
            
            @auth
                <div class="flex items-center gap-3 bg-slate-950/60 border border-slate-800 px-3.5 py-1.5 rounded-2xl">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-bold text-slate-950 text-xs shadow-md">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col text-right">
                        <span class="text-xs font-bold text-white leading-tight">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-emerald-400 font-mono uppercase">{{ Auth::user()->role }}</span>
                    </div>
                </div>

                <!-- زر تسجيل الخروج عبر Livewire -->
                <button wire:click="logout" class="p-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 rounded-xl transition shadow-inner text-xs flex items-center gap-1 font-medium" title="تسجيل الخروج">
                    <span>🚪 خروج</span>
                </button>
            @endauth

            <!-- مؤشر النشاط -->
            <span class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 text-xs font-medium text-emerald-400 rounded-xl">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                نشط
            </span>

        </div>

    </div>
</nav>