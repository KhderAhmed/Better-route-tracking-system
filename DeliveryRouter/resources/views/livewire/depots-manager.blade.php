<div class="bg-slate-900 text-slate-100 min-h-screen p-6 font-sans antialiased" dir="rtl">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- الهيدر -->
   @livewire('navbar') 
        @if (session()->has('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- فورم إضافة المستودع -->
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl h-fit space-y-4 lg:col-span-1">
                <h2 class="text-lg font-bold text-emerald-400 border-b border-slate-700 pb-3">إضافة مستودع رئيسي جديد</h2>

                <form wire:submit.prevent="saveDepot" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">اسم المستودع *</label>
                        <input type="text" wire:model.defer="name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="مثال: المستودع المركزي - طرطوس">
                        @error('name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">العنوان التفصيلي</label>
                        <input type="text" wire:model.defer="address" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="المنطقة الصناعية، الشارع العام">
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs bg-slate-900/80 border border-slate-700 p-3 rounded-xl font-mono text-slate-300">
                        <div><span class="text-emerald-400 font-sans font-bold">Lat:</span> {{ number_format((float)$latitude, 5) }}</div>
                        <div><span class="text-emerald-400 font-sans font-bold">Lng:</span> {{ number_format((float)$longitude, 5) }}</div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm rounded-xl shadow-lg transition">
                        حفظ وتثبيت المستودع ⚡
                    </button>
                </form>
            </div>

            <!-- خريطة اختيار موقع المستودع والقائمة -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-sm font-bold text-slate-200 mb-3 flex items-center gap-2">
                        <span>📍</span> انقر على الخريطة لتحديد موقع المستودع بدقة:
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

                <!-- جدول المستودعات المسجلة -->
                <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-sm font-bold text-emerald-400 mb-4">المستودعات المسجلة في النظام</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-right text-slate-300">
                            <thead class="bg-slate-900/60 text-slate-400 uppercase text-xs border-b border-slate-700">
                                <tr>
                                    <th class="p-3">اسم المستودع</th>
                                    <th class="p-3">العنوان</th>
                                    <th class="p-3">الإحداثيات</th>
                                    <th class="p-3">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50">
                                @forelse($depots as $depot)
                                    <tr class="hover:bg-slate-700/30 transition">
                                        <td class="p-3 font-bold text-white">{{ $depot->name }}</td>
                                        <td class="p-3 text-slate-300">{{ $depot->address ?? '-' }}</td>
                                        <td class="p-3 font-mono text-xs text-sky-400">{{ $depot->latitude }}, {{ $depot->longitude }}</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $depot->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700 text-slate-400' }}">
                                                {{ $depot->is_active ? 'نشط (Active)' : 'معطل' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-slate-500 italic">لا توجد مستودعات مضافة بعد.</td>
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

