<div class="bg-slate-950 text-slate-100 min-h-screen pb-12 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950" dir="rtl">
    
    <!-- Navbar علوي عصري -->
    @livewire('navbar') 

    <!-- محتوى المخططات -->
    <div class="max-w-7xl mx-auto px-6 space-y-8 mt-6">

        <!-- شبكة المخططات الأربعة المصممة باحترافية -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- 1. مخطط حالات العربات -->
            <div class="relative bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl hover:border-slate-700/80 transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="p-2 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20">🚛</span> 
                            كفاءة وتوزيع الأسطول
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">جاهزية العربات والحالات التشغيلية الحالية</p>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="vehiclesChart"></canvas>
                </div>
            </div>

            <!-- 2. مخطط حالات الطلبات -->
            <div class="relative bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl hover:border-slate-700/80 transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="p-2 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20">📦</span> 
                            مؤشرات حالات الطلبات
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">متابعة دقيقة لنسب إنجاز وشحن الطلبيات</p>
                    </div>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- 3. مخطط المسارات المحسوبة -->
            <div class="relative bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl hover:border-slate-700/80 transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">🗺️</span> 
                            تحليل أداء وتنفيذ المسارات
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">نسب المسارات المخططة والمكتملة ميدانياً</p>
                    </div>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="routesChart"></canvas>
                </div>
            </div>

            <!-- 4. مخطط المستودعات -->
            <div class="relative bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl hover:border-slate-700/80 transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="p-2 bg-purple-500/10 text-purple-400 rounded-xl border border-purple-500/20">🏭</span> 
                            نشاط مراكز التوزيع والمستودعات
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">توزيع جاهزية نقاط الانطلاق المركزية</p>
                    </div>
                </div>
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="depotsChart"></canvas>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- تضمين مكتبة Chart.js v4 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        Chart.defaults.font.family = 'ui-sans-serif, system-ui, sans-serif';
        Chart.defaults.color = '#94a3b8';

        // 1. مخطط الأسطول
        const ctxVehicles = document.getElementById('vehiclesChart').getContext('2d');
        const gradientAvailable = ctxVehicles.createLinearGradient(0, 0, 0, 200);
        gradientAvailable.addColorStop(0, '#10b981');
        gradientAvailable.addColorStop(1, 'rgba(16, 185, 129, 0.1)');

        const gradientOnRoute = ctxVehicles.createLinearGradient(0, 0, 0, 200);
        gradientOnRoute.addColorStop(0, '#0284c7');
        gradientOnRoute.addColorStop(1, 'rgba(2, 132, 199, 0.1)');

        const gradientMaintenance = ctxVehicles.createLinearGradient(0, 0, 0, 200);
        gradientMaintenance.addColorStop(0, '#f43f5e');
        gradientMaintenance.addColorStop(1, 'rgba(244, 63, 94, 0.1)');

        new Chart(ctxVehicles, {
            type: 'bar',
            data: {
                labels: ['متاح (Available)', 'في الطريق (On Route)', 'قيد الصيانة (Maintenance)'],
                datasets: [{
                    data: [{{ $availableVehicles }}, {{ $onRouteVehicles }}, {{ $maintenanceVehicles }}],
                    backgroundColor: [gradientAvailable, gradientOnRoute, gradientMaintenance],
                    borderColor: ['#10b981', '#0284c7', '#f43f5e'],
                    borderWidth: 2,
                    borderRadius: 12,
                    borderSkipped: false,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(51, 65, 85, 0.4)' }, ticks: { font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });

        // 2. مخطط الطلبات
        new Chart(document.getElementById('ordersChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['قيد الانتظار', 'معين', 'تم التوصيل', 'ملغي'],
                datasets: [{
                    data: [{{ $pendingOrders }}, {{ $assignedOrders }}, {{ $deliveredOrders }}, {{ $cancelledOrders }}],
                    backgroundColor: ['#f59e0b', '#38bdf8', '#10b981', '#ef4444'],
                    borderColor: '#0f172a',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });

        // 3. مخطط المسارات
        new Chart(document.getElementById('routesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['تم الحساب', 'مكتملة التنفيذ', 'قيد الانتظار'],
                datasets: [{
                    data: [{{ $calculatedRoutes }}, {{ $completedRoutes }}, {{ $pendingRoutes }}],
                    backgroundColor: ['#0284c7', '#10b981', '#f59e0b'],
                    borderColor: '#0f172a',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });

        // 4. مخطط المستودعات
        new Chart(document.getElementById('depotsChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['نشط (Active)', 'معطل (Inactive)'],
                datasets: [{
                    data: [{{ $activeDepots }}, {{ $inactiveDepots }}],
                    backgroundColor: ['#8b5cf6', '#475569'],
                    borderColor: '#0f172a',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>