<div class="bg-slate-900 text-slate-100 min-h-screen p-6 font-sans antialiased" dir="rtl">
    <div class="max-w-7xl mx-auto space-y-6">

     @livewire('navbar') 

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- العمود الأيمن: النماذج (الطلب + إدخال الشاحنة والسائق) -->
            <div class="space-y-6 lg:col-span-1">
                
                <!-- نموذج إضافة طلب جديد -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl space-y-4">
                    <h2 class="text-lg font-bold text-white border-b border-slate-700 pb-3 flex items-center gap-2">
                        <span>📦</span> إضافة طلب توصيل جديد
                    </h2>

                    @if (session()->has('success_order'))
                        <div class="p-3 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl font-medium">
                            {{ session('success_order') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="createOrder" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">اسم الزبون *</label>
                            <input type="text" wire:model.defer="customer_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="أدخل اسم الزبون">
                            @error('customer_name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">رقم الهاتف</label>
                            <input type="text" wire:model.defer="customer_phone" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="09xxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">عنوان التوصيل (نصي) *</label>
                            <input type="text" wire:model.defer="delivery_address" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="مثال: طرطوس - الكورنيش">
                            @error('delivery_address') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">وزن الطلبية (كغ) *</label>
                            <input type="number" step="0.1" wire:model.defer="weight_kg" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <!-- شاشة الإحداثيات المصغرة -->
                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-900/80 border border-slate-700 p-3 rounded-xl font-mono text-slate-300">
                            <div><span class="text-blue-400 font-sans font-bold">Lat:</span> {{ number_format((float)$latitude, 5) }}</div>
                            <div><span class="text-blue-400 font-sans font-bold">Lng:</span> {{ number_format((float)$longitude, 5) }}</div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition">
                            حفظ الطلب وتثبيت الموقع 🚀
                        </button>
                    </form>
                </div>

         
            </div>

            <!-- قسم الخريطة والجدول -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- الخريطة التفاعلية -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-sm font-bold text-slate-200 mb-3 flex items-center gap-2">
                        <span>📍</span> انقر في أي مكان على الخريطة لتحديد موقع التسليم الدقيق:
                    </h3>
                    
                    <div 
                        x-data="{
                            map: null,
                            marker: null,
                            initMap() {
                                this.map = L.map($refs.mapContainer).setView([{{ $latitude }}, {{ $longitude }}], 13);
                                
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap'
                                }).addTo(this.map);

                                this.marker = L.marker([{{ $latitude }}, {{ $longitude }}], {draggable: true}).addTo(this.map);

                                this.map.on('click', (e) => {
                                    const lat = e.latlng.lat;
                                    const lng = e.latlng.lng;
                                    this.marker.setLatLng([lat, lng]);
                                    Livewire.dispatch('locationSelected', { lat: lat, lng: lng });
                                });

                                this.marker.on('dragend', (e) => {
                                    const position = this.marker.getLatLng();
                                    Livewire.dispatch('locationSelected', { lat: position.lat, lng: position.lng });
                                });
                            }
                        }"
                        x-init="initMap()"
                        wire:ignore
                    >
                        <div x-ref="mapContainer" class="w-full h-80 rounded-xl border border-slate-700 z-10 shadow-inner"></div>
                    </div>
                </div>

                <!-- جدول أحدث الطلبات -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
                        <span>📋</span> الطلبات المسجلة حديثاً
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-right text-slate-300">
                            <thead class="bg-slate-900/60 text-slate-400 uppercase text-xs border-b border-slate-700">
                                <tr>
                                    <th class="p-3">الزبون</th>
                                    <th class="p-3">الهاتف</th>
                                    <th class="p-3">العنوان</th>
                                    <th class="p-3">الوزن</th>
                                    <th class="p-3">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-slate-700/30 transition">
                                        <td class="p-3 font-semibold text-white">{{ $order->customer_name }}</td>
                                        <td class="p-3 dir-ltr text-right text-slate-400">{{ $order->customer_phone ?? '-' }}</td>
                                        <td class="p-3 text-slate-300">{{ $order->delivery_address }}</td>
                                        <td class="p-3 font-medium text-blue-400">{{ $order->weight_kg }} كغ</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 text-xs bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full font-semibold">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-slate-500 italic">لا توجد طلبات مسجلة بعد. أضف طلبك الأول!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- تضمين مكتبة Leaflet الأساسية -->
