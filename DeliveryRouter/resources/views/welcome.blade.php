<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeliveryRouter - نظام إدارة وتوجيه التوصيل الذكي</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- الهيدر وشريط التنقل العلوي -->
    <header class="max-w-7xl mx-auto w-full px-6 py-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🚚</span>
            <span class="text-xl font-bold tracking-wider text-blue-400">DeliveryRouter</span>
        </div>
        <div>


                  <div class="flex items-center gap-3">
                <a href="{{ route('delivery.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold px-4 py-2 rounded-xl transition shadow">
                    إضافة طلب جديد 📦
                </a>
                  <a href="{{ route('route.planner') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold px-4 py-2 rounded-xl transition shadow">
                عرض الطلبات
                </a>
                
                <a href="{{ url('/') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold px-4 py-2 rounded-xl transition shadow">
                    الرئيسية 🏠
                </a>
            </div>
            {{-- @if (Route::has('login'))
                <div class="space-x-4 space-x-reverse">
                    @auth
                        <a href="{{ url('/delivery-dashboard') }}" class="bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg font-medium transition shadow-lg shadow-blue-500/20">لوحة التحكم</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-4 py-2 font-medium transition">تسجيل الدخول</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg font-medium transition shadow-lg shadow-blue-500/20">حساب جديد</a>
                        @endif
                    @endauth
                </div>
            @endif --}}
        </div>
    </header>

    <!-- القسم الرئيسي (Hero Section) -->
    <main class="max-w-5xl mx-auto px-6 py-16 text-center space-y-8">
        <div class="inline-block bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold px-3 py-1.5 rounded-full">
            مدعوم بخوارزميات SFLA وشبكة طرق OSRM الحقيقية 🚀
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white leading-tight">
            نظام إدارة وتوجيه التوصيل <span class="text-blue-500">الذكي في طرطوس</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
            منصة متكاملة لتحديد مواقع الطلبات تفاعلياً على الخريطة، حساب أقصر المسارات بالاعتماد على شبكة الطرق الفعلية، وتحسين أداء الأسطول بكفاءة عالية.
        </p>

        <div class="flex justify-center gap-4 pt-4">
            <a href="{{ url('/delivery-dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5">
                الدخول للداشبورد المباشر 🗺️
            </a>
        </div>

        <!-- مميزات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-12 text-right">
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl">
                <div class="text-3xl mb-3">📍</div>
                <h3 class="font-bold text-lg mb-2 text-white">تحديد تفاعلي</h3>
                <p class="text-sm text-slate-400">إضافة الطلبات وتحديد إحداثيات الزبائن بدقة مباشرة بالنقر على خريطة Leaflet.</p>
            </div>
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl">
                <div class="text-3xl mb-3">⚡</div>
                <h3 class="font-bold text-lg mb-2 text-white">توجيه حقيقي (OSRM)</h3>
                <p class="text-sm text-slate-400">الاعتماد على شبكة الشوارع والتقاطعات الفعلية بدلاً من مسافات الخطوط المستقيمة النظرية.</p>
            </div>
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl">
                <div class="text-3xl mb-3">📊</div>
                <h3 class="font-bold text-lg mb-2 text-white">إدارة متكاملة</h3>
                <p class="text-sm text-slate-400">لوحة تحكم مرنة تتابع حالات الطلبات، الأوزان، وأداء السائقين بكل سهولة.</p>
            </div>
        </div>
    </main>

    <!-- الفوتر -->
    <footer class="border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        جميع الحقوق محفوظة © 2026 - DeliveryRouter Platform
    </footer>

</body>
</html>