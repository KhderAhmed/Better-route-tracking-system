<div class="bg-slate-900 text-slate-100 min-h-screen p-6 font-sans antialiased" dir="rtl">
    <!-- Leaflet CSS -->

    <div class="max-w-7xl mx-auto space-y-6">

         @livewire('navbar') 

        <!-- رسائل التنبيه والنجاح -->
        @if (session()->has('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 font-medium shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 font-medium shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- لوحة التحكم والمدخلات -->
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl space-y-6 h-fit">
                <h2 class="text-lg font-bold text-emerald-400 border-b border-slate-700 pb-3 flex items-center gap-2">
                    <span>⚡</span> إعدادات المحرك والأسطول
                </h2>

                <!-- إحصائيات سريعة -->
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-700/60">
                        <span class="block text-xs text-slate-400 mb-1">الطلبات قيد الانتظار</span>
                        <span class="text-lg font-bold text-emerald-400">{{ $pendingOrdersCount }}</span>
                    </div>
                    <div class="bg-slate-900/80 p-3 rounded-xl border border-slate-700/60">
                        <span class="block text-xs text-slate-400 mb-1">الشاحنات المتاحة</span>
                        <span class="text-lg font-bold text-sky-400">{{ $vehiclesCount }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">عدد الشاحنات المستخدمة</label>
                        <input type="number" wire:model="numVehicles" min="1"
                            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">السعة القصوى للشاحنة (كغ)</label>
                        <input type="number" wire:model="vehicleCapacity" step="50"
                            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <button wire:click="runOptimization" wire:loading.attr="disabled"
                    class="w-full bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 text-slate-950 font-bold p-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 transform hover:-translate-y-0.5">
                    <span wire:loading.remove>حساب المسارات وتخصيص الشاحنات 🚀</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-slate-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                       ... SFLA جاري المعالجة بمحرك 
                    </span>
                </button>

                @if ($optimizationResult)
                    <div class="p-4 bg-slate-950/80 rounded-xl border border-slate-700/60 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">المسافة الإجمالية:</span>
                            <span class="font-bold text-emerald-400">{{ $optimizationResult['total_distance_km'] ?? 'N/A' }} كم</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">حالة الحل:</span>
                            <span class="font-semibold {{ ($optimizationResult['is_feasible'] ?? false) ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ ($optimizationResult['is_feasible'] ?? false) ? 'مطابق للشروط (Feasible)' : 'تجاوز في الحمولة' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- الخريطة التفاعلية -->
            <div class="lg:col-span-2 bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
                <h3 class="text-sm font-bold text-slate-200 mb-3 flex items-center gap-2">
                    <span>📍</span> خريطة توجيه الشاحنات وتتبع خطوط السير الحية:
                </h3>
                <div class="w-full h-[490px] rounded-xl border border-slate-700 z-10 shadow-inner relative" wire:ignore>
                    <div id="map" class="w-full h-full rounded-xl"></div>
                </div>
            </div>
        </div>

        <!-- جدول المسارات المحسوبة حديثاً -->
        @if (count($routesList) > 0)
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-emerald-400 mb-4 flex items-center gap-2">
                    <span>📋</span> آخر خطط المسارات المخصصة للشاحنات
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm text-slate-300">
                        <thead class="bg-slate-900/60 text-slate-400 uppercase text-xs border-b border-slate-700">
                            <tr>
                                <th class="p-3">رقم المسار</th>
                                <th class="p-3">الشاحنة / السائق</th>
                                <th class="p-3">إجمالي الحمولة</th>
                                <th class="p-3">المسافة الإجمالية</th>
                                <th class="p-3">عدد المحطات</th>
                                <th class="p-3">حالة المسار</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach ($routesList as $route)
                                <tr class="hover:bg-slate-700/30 transition">
                                    <td class="p-3 font-bold text-white">#{{ $route->id }}</td>
                                    <td class="p-3 text-slate-200">{{ $route->vehicle?->driver_name ?? 'غير معين' }} ({{ $route->vehicle?->plate_number ?? 'N/A' }})</td>
                                    <td class="p-3 font-semibold text-emerald-400">{{ $route->total_weight_kg }} كغ</td>
                                    <td class="p-3 text-sky-400">{{ $route->total_distance_km }} كم</td>
                                    <td class="p-3 text-slate-300">{{ $route->stops->count() }} طلبات</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                            {{ $route->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- Leaflet JS Script -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. تهيئة الخريطة الأساسية (مركزة على طرطوس افتراضياً)
            const map = L.map('map').setView([34.8886, 35.8866], 13);

            // تحميل بلاط خريطة OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            window.routeLayers = [];

            // 2. الاستماع لحدث البيانات القادم من Livewire
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('routesCalculated', (event) => {
                    const data = event.data || event[0]?.data; // توافقية إصدارات Livewire
                    if (!data) return;

                    // مسح أي مسارات قديمة على الخريطة لتجنب التراكب والتداخل
                    if (window.routeLayers) {
                        window.routeLayers.forEach(layer => map.removeLayer(layer));
                    }
                    window.routeLayers = [];

                    const depot = data.depot;
                    const colors = ['#10b981', '#38bdf8', '#f59e0b', '#ec4899', '#8b5cf6']; // ألوان متناسقة مع الداشبورد الداكن

                    // إضافة علامة المستودع الرئيسي
                    const depotMarker = L.marker([depot.lat, depot.lng]).addTo(map)
                        .bindPopup(`<div dir="rtl"><b>المستودع الرئيسي:</b> ${depot.name}</div>`);
                    window.routeLayers.push(depotMarker);

                    // رسم المسارات والخطوط لكل شاحنة
                    data.routes.forEach((route, index) => {
                        const color = colors[index % colors.length];
                        let latLngs = [[depot.lat, depot.lng]]; // بدء المسار من المستودع

                        route.stops.forEach(stop => {
                            latLngs.push([stop.lat, stop.lng]);
                            
                            // إضافة Marker دائري لكل طلب/محطة
                            const marker = L.circleMarker([stop.lat, stop.lng], {
                                radius: 7,
                                color: color,
                                fillColor: color,
                                fillOpacity: 0.9,
                                weight: 2
                            }).addTo(map).bindPopup(`<div dir="rtl"><b>رقم الطلب:</b> #${stop.order_id}<br><b>الوزن:</b> ${stop.weight_kg} كغ</div>`);
                            
                            window.routeLayers.push(marker);
                        });

                        // العودة إلى المستودع لإغلاق الدورة اللوجستية للمسار
                        latLngs.push([depot.lat, depot.lng]);

                        // رسم الخط الملون (Polyline) للمسار الخاص بالشاحنة
                        const polyline = L.polyline(latLngs, {
                            color: color,
                            weight: 4,
                            opacity: 0.85,
                            dashArray: '5, 5' // خط متقطع أنيق يوضح مسار السير
                        }).addTo(map).bindPopup(`<div dir="rtl"><b>مسار الشاحنة رقم:</b> ${route.vehicle_id}</div>`);

                        window.routeLayers.push(polyline);
                    });
                });
            });
        });
    </script>


</div>