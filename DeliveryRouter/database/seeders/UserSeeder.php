<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. حساب مدير النظام الأقصى (Super Admin)
        User::updateOrCreate(
            ['email' => 'admin@system.local'],
            [
                'name'              => 'مدير النظام الرئيسي',
                'email_verified_at' => now(),
                'password'          => Hash::make('Admin@SecurePass2026!'), // كلمة سر معقدة
                'role'              => 'admin',
                'is_active'         => true,
                'remember_token'    => Str::random(10),
            ]
        );

        // 2. حساب مدير تشغيلي (Manager)
        User::updateOrCreate(
            ['email' => 'manager@system.local'],
            [
                'name'              => 'مدير العمليات',
                'email_verified_at' => now(),
                'password'          => Hash::make('ManagerPass#2026'),
                'role'              => 'manager',
                'is_active'         => true,
                'remember_token'    => Str::random(10),
            ]
        );

        // 3. مستخدمون تجريبيون للتست (Customers / Drivers)
        User::factory(10)->create();
    }
}