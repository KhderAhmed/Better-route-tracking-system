<div class="bg-slate-950 text-slate-100 min-h-screen pb-12 font-sans antialiased selection:bg-emerald-500 selection:text-slate-950" dir="rtl">
    
    <!-- شريط التنقل العلوي -->
    @livewire('navbar')

    <div class="max-w-7xl mx-auto px-6 space-y-6 mt-6">

        <!-- الهيدر -->
        <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl backdrop-blur-xl shadow-2xl flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black text-white flex items-center gap-2">
                    <span>👥</span> إدارة المستخدمين وصلاحيات الأمان (RBAC)
                </h1>
                <p class="text-xs text-slate-400 mt-1">التحكم بحسابات الإداريين، السائقين، والمديرين وتفعيلهم</p>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-xs font-semibold shadow-inner">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-xs font-semibold shadow-inner">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- فورم إضافة مستخدم جديد -->
            <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl h-fit space-y-4 lg:col-span-1">
                <h2 class="text-sm font-bold text-emerald-400 border-b border-slate-800 pb-3 flex items-center gap-2">
                    <span>➕</span> إضافة مستخدم جديد
                </h2>

                <form wire:submit.prevent="saveUser" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">الاسم الكامل *</label>
                        <input type="text" wire:model.defer="name" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-2.5 text-white focus:outline-none focus:border-emerald-500" placeholder="اسم المستخدم">
                        @error('name') <span class="text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">البريد الإلكتروني *</label>
                        <input type="email" wire:model.defer="email" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-2.5 text-white focus:outline-none focus:border-emerald-500" placeholder="user@system.local">
                        @error('email') <span class="text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">كلمة المرور *</label>
                        <input type="password" wire:model.defer="password" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-2.5 text-white focus:outline-none focus:border-emerald-500" placeholder="••••••••">
                        @error('password') <span class="text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">الدور والصلاحية *</label>
                        <select wire:model="role" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-2.5 text-white focus:outline-none focus:border-emerald-500">
                            <option value="admin">مدير النظام (Admin)</option>
                            <option value="manager">مدير عمليات (Manager)</option>
                            <option value="driver">سائق (Driver)</option>
                            <option value="customer">زبون (Customer)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 text-slate-950 font-black rounded-xl shadow-lg shadow-emerald-500/20 transition">
                        حفظ وإنشاء الحساب ⚡
                    </button>
                </form>
            </div>

            <!-- جدول المستخدمين -->
            <div class="bg-slate-900/60 border border-slate-800/80 p-6 rounded-3xl shadow-2xl backdrop-blur-xl lg:col-span-2 space-y-4">
                <h2 class="text-sm font-bold text-white">قائمة حسابات المستخدمين في النظام</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-right text-slate-300">
                        <thead class="bg-slate-950/60 text-slate-400 uppercase border-b border-slate-800">
                            <tr>
                                <th class="p-3">الاسم</th>
                                <th class="p-3">البريد الإلكتروني</th>
                                <th class="p-3">الصلاحية (تعديل مباشر)</th>
                                <th class="p-3">الحالة</th>
                                <th class="p-3 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="p-3 font-bold text-white">{{ $user->name }}</td>
                                    <td class="p-3 text-slate-400 font-mono">{{ $user->email }}</td>
                                    <td class="p-3">
                                        <!-- ربط كل قائمة بمصفوفة مخصصة لمعرف المستخدم لتكون مستقلة تماماً -->
                                        <select wire:model="userRoles.{{ $user->id }}" wire:change="updateRole({{ $user->id }})" 
                                            class="bg-slate-950 border rounded-lg px-2.5 py-1.5 text-xs font-mono uppercase font-bold focus:outline-none transition
                                            @if(($userRoles[$user->id] ?? $user->role) == 'admin') border-rose-500/30 text-rose-400
                                            @elseif(($userRoles[$user->id] ?? $user->role) == 'manager') border-amber-500/30 text-amber-400
                                            @else border-sky-500/30 text-sky-400 @endif">
                                            <option value="admin">Admin</option>
                                            <option value="manager">Manager</option>
                                            <option value="driver">Driver</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <button wire:click="toggleActive({{ $user->id }})" class="px-2.5 py-1 rounded-lg text-[11px] transition {{ $user->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                            {{ $user->is_active ? 'نشط' : 'معطل' }}
                                        </button>
                                    </td>
                                    <td class="p-3 text-center">
                                        <button wire:click="deleteUser({{ $user->id }})" class="px-2.5 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg text-[11px] transition">
                                            حذف
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 italic">لا توجد حسابات مسجلة حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>