<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Depot;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\Router;
use App\Models\RouterStop;
use Illuminate\Support\Facades\Schema;

class TartousDeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. تعطيل قيود المفاتيح الأجنبية بطريقة متوافقة مع SQLite و MySQL
        Schema::disableForeignKeyConstraints();

        // تنظيف البيانات القديمة
        RouterStop::truncate();
        Router::truncate();
        Order::truncate();
        Vehicle::truncate();
        Depot::truncate();

        // إعادة تفعيل قيود المفاتيح الأجنبية
        Schema::enableForeignKeyConstraints();

        // 2. إنشاء المستودع الرئيسي (قرب مرفأ طرطوس)
        $depot = Depot::create([
            'name' => 'المستودع الرئيسي - مرفأ طرطوس',
            'address' => 'طرطوس - المنطقة الجمركية قرب المرفأ',
            'latitude' => 34.891200,
            'longitude' => 35.875500,
            'is_active' => true,
        ]);

        // 3. إنشاء أسطول الشاحنات
        $vehicles = [
            [
                'plate_number' => 'طرطوس-10245',
                'driver_name' => 'أحمد علي',
                'capacity_kg' => 500.00,
                'status' => 'available',
            ],
            [
                'plate_number' => 'طرطوس-88421',
                'driver_name' => 'حسام محمود',
                'capacity_kg' => 500.00,
                'status' => 'available',
            ],
            [
                'plate_number' => 'طرطوس-55109',
                'driver_name' => 'خالد سليمان',
                'capacity_kg' => 500.00,
                'status' => 'available',
            ],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create($v);
        }

        // 4. نقاط وإحداثيات واقعية لأحياء ومناطق طرطوس
        $tartousLocations = [
            ['name' => 'علي حسن', 'address' => 'شارع الثورة - قرب شارع العريض', 'lat' => 34.8950, 'lng' => 35.8820],
            ['name' => 'محمد سعيد', 'address' => 'حي المشبك السفلي', 'lat' => 34.8870, 'lng' => 35.8890],
            ['name' => 'سامر إبراهيم', 'address' => 'حي المشروع السادس', 'lat' => 34.9010, 'lng' => 35.8920],
            ['name' => 'رامي يوسف', 'address' => 'الكورنيش البحري - مقابل المرفأ', 'lat' => 34.8980, 'lng' => 35.8720],
            ['name' => 'فادي خليل', 'address' => 'حي الحمرات - قرب الدوار', 'lat' => 34.8820, 'lng' => 35.8810],
            ['name' => 'عمر القاسم', 'address' => 'حي الإنشاءات', 'lat' => 34.8760, 'lng' => 35.8850],
            ['name' => 'زياد عيسى', 'address' => 'شارع المينا - قرب القلعة القديمة', 'lat' => 34.8925, 'lng' => 35.8740],
            ['name' => 'ماهر صالح', 'address' => 'حي الغدير', 'lat' => 34.8840, 'lng' => 35.8950],
            ['name' => 'كرم حداد', 'address' => 'طريق أرواد - المرفأ القديم', 'lat' => 34.8890, 'lng' => 35.8710],
            ['name' => 'طارق حمود', 'address' => 'حي الرمل - جنوب طرطوس', 'lat' => 34.8710, 'lng' => 35.8790],
            ['name' => 'ياسر عباس', 'address' => 'المنطقة الصناعية - طرطوس', 'lat' => 34.8650, 'lng' => 35.8910],
            ['name' => 'نضال عمران', 'address' => 'حي الجمعية السكنية', 'lat' => 34.9080, 'lng' => 35.8980],
            ['name' => 'أيمن شعبان', 'address' => 'مدخل طرطوس الشمالي', 'lat' => 34.9150, 'lng' => 35.8900],
            ['name' => 'باسل منصور', 'address' => 'شارع 8 آذار', 'lat' => 34.8900, 'lng' => 35.8840],
            ['name' => 'وائل جديد', 'address' => 'جامعة طرطوس - الكليات والمعاهد', 'lat' => 34.8800, 'lng' => 35.8980],
        ];

        // 5. إدراج الطلبات
        foreach ($tartousLocations as $loc) {
            Order::create([
                'customer_name' => $loc['name'],
                'customer_phone' => '099' . rand(1000000, 9999999),
                'delivery_address' => $loc['address'],
                'latitude' => $loc['lat'],
                'longitude' => $loc['lng'],
                'weight_kg' => rand(30, 95),
                'status' => 'pending',
            ]);
        }

        $this->command->info('تم زراعة بيانات طرطوس والمستودع والطلبات بنجاح!');
    }
}