<div class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950" dir="rtl">
    
    <!-- خلفية جمالية متوهجة -->
    <div class="absolute w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -top-20 -right-20"></div>
    <div class="absolute w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -bottom-20 -left-20"></div>

    <div class="relative w-full max-w-md space-y-6">
        
        <!-- الهيدر والشعار -->
        <div class="text-center space-y-2">
            <div class="inline-flex p-3 bg-gradient-to-tr from-emerald-500 to-sky-500 rounded-2xl shadow-xl shadow-emerald-500/20 text-3xl mb-1">
                🚀
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">تسجيل الدخول للنظام</h1>
            <p class="text-xs text-slate-400">أدخل بيانات حسابك للوصول إلى لوحة التحكم والأسطول</p>
        </div>

        <!-- كارد الفورم الزجاجي -->
        <div class="bg-slate-900/60 border border-slate-800/80 p-8 rounded-3xl shadow-2xl backdrop-blur-2xl space-y-6">
            
            <form wire:submit.prevent="login" class="space-y-4">
                
                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">البريد الإلكتروني</label>
                    <input type="email" wire:model.defer="email" 
                        class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl p-3 text-sm text-white placeholder-slate-600 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:outline-none transition" 
                        placeholder="name@example.com">
                    @error('email') <span class="text-xs text-rose-400 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">كلمة المرور</label>
                    <input type="password" wire:model.defer="password" 
                        class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl p-3 text-sm text-white placeholder-slate-600 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:outline-none transition" 
                        placeholder="••••••••">
                    @error('password') <span class="text-xs text-rose-400 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- تذكرني -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                        <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                        <span>تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <!-- زر الدخول -->
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full mt-2 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-sm rounded-2xl shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 disabled:opacity-50">
                    <span wire:loading.remove>دخول آمن ⚡</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-slate-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        جاري التحقق...
                    </span>
                </button>

            </form>

        </div>

        <!-- تذييل الصفحة -->
        <div class="text-center text-xs text-slate-500">
            نظام إدارة الأسطول والطلبات اللوجستية &copy; 2026
        </div>

    </div>
</div>