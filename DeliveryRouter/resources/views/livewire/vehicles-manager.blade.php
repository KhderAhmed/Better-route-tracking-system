<div class="bg-slate-900 text-slate-100 min-h-screen p-6 font-sans antialiased" dir="rtl">
    <div class="max-w-7xl mx-auto space-y-6">

   @livewire('navbar') 
   
        @if (session()->has('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- فورم إضافة شاحنة جديدة -->
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl h-fit space-y-4 lg:col-span-1">
                <h2 class="text-lg font-bold text-emerald-400 border-b border-slate-700 pb-3">إضافة شاحنة جديدة للأسطول</h2>

                <form wire:submit.prevent="saveVehicle" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">اسم السائق *</label>
                        <input type="text" wire:model.defer="driver_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="اسم السائق الكامل">
                        @error('driver_name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">رقم اللوحة *</label>
                        <input type="text" wire:model.defer="plate_number" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="مثال: طرطوس 554433">
                        @error('plate_number') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">الحمولة القصوى (كغ) *</label>
                        <input type="number" wire:model.defer="capacity_kg" step="50" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('capacity_kg') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">الحالة التشغيلية *</label>
                        <select wire:model.defer="status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="available">متاح (Available)</option>
                            <option value="on_route">في الطريق (On Route)</option>
                            <option value="maintenance">قيد الصيانة (Maintenance)</option>
                        </select>
                        @error('status') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm rounded-xl shadow-lg transition">
                        حفظ وإضافة الشاحنة ⚡
                    </button>
                </form>
            </div>

            <!-- جدول عرض كل الشاحنات -->
            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-xl lg:col-span-2">
                <h2 class="text-lg font-bold text-slate-200 mb-4">قائمة شاحنات الأسطول المسجلة</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-slate-300">
                        <thead class="bg-slate-900/60 text-slate-400 uppercase text-xs border-b border-slate-700">
                            <tr>
                                <th class="p-3">رقم اللوحة</th>
                                <th class="p-3">السائق</th>
                                <th class="p-3">الحمولة</th>
                                <th class="p-3">الحالة</th>
                                <th class="p-3 text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @forelse($vehicles as $v)
                                <tr class="hover:bg-slate-700/30 transition">
                                    <td class="p-3 font-bold text-white">{{ $v->plate_number }}</td>
                                    <td class="p-3 text-slate-200">{{ $v->driver_name }}</td>
                                    <td class="p-3 text-sky-400">{{ $v->capacity_kg }} كغ</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 text-xs rounded-full font-semibold 
                                            @if($v->status == 'available') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 
                                            @elseif($v->status == 'on_route') bg-sky-500/10 text-sky-400 border border-sky-500/20 
                                            @else bg-rose-500/10 text-rose-400 border border-rose-500/20 @endif">
                                            {{ $v->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <button wire:click="deleteVehicle({{ $v->id }})" class="px-3 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg text-xs transition">
                                            حذف
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 italic">لا توجد شاحنات مضافة حتى الآن.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>