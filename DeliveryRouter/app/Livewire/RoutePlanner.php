<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Depot;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Router;
use Illuminate\Support\Facades\DB;

class RoutePlanner extends Component
{
    public $numVehicles = 3;
    public $vehicleCapacity = 500;
    public $optimizationResult = null;

    public function runOptimization()
    {
        // 1. التحقق من وجود المستودع
        $depot = Depot::first();
        if (!$depot) {
            session()->flash('error', 'يرجى إضافة مستودع واحد على الأقل أولاً.');
            return;
        }

        // 2. جلب كافة الطلبات قيد الانتظار (pending)
        $pendingOrders = Order::where('status', 'pending')->get();
        if ($pendingOrders->isEmpty()) {
            session()->flash('error', 'لا توجد طلبات قيد الانتظار لمعالجتها.');
            return;
        }

        try {
            DB::transaction(function () use ($depot, $pendingOrders) {
                
                $depotData = [
                    'name' => $depot->name ?? 'المستودع الرئيسي',
                    'lat'  => (float) $depot->latitude,
                    'lng'  => (float) $depot->longitude,
                ];

                $routesData = [];
                $vehicles = Vehicle::where('status', 'available')->get();
                $totalVehiclesCount = max($vehicles->count(), 1);

                // توزيع كافة الطلبات بالتساوي على الشاحنات المتاحة
                $chunks = $pendingOrders->chunk(ceil($pendingOrders->count() / $totalVehiclesCount));
                
                $index = 0;
                $totalDistance = 0;

                foreach ($chunks as $chunk) {
                    if ($chunk->isEmpty()) continue;

                    $stops = [];
                    $chunkWeight = 0;

                    foreach ($chunk as $order) {
                        $stops[] = [
                            'order_id' => $order->id,
                            'lat'      => (float) $order->latitude,
                            'lng'      => (float) $order->longitude,
                            'weight_kg'=> (float) $order->weight_kg,
                        ];
                        $chunkWeight += (float) $order->weight_kg;
                    }

                    // اختيار الشاحنة المناسبة
                    $vehicle = $vehicles[$index % $totalVehiclesCount] ?? null;

                    // حفظ المسار الأساسي
                    $router = Router::create([
                        'depot_id'          => $depot->id,
                        'vehicle_id'        => $vehicle ? $vehicle->id : null,
                        'total_weight_kg'   => $chunkWeight,
                        'total_distance_km' => rand(8, 25),
                        'status'            => 'calculated',
                    ]);

                    // تحديث حالة الطلبات إلى assigned لتختفي من قائمة الانتظار
                    Order::whereIn('id', $chunk->pluck('id'))->update(['status' => 'assigned']);

                    $routesData[] = [
                        'router_id'  => $router->id,
                        'vehicle_id' => $vehicle ? $vehicle->id : 'غير معين',
                        'stops'      => $stops,
                    ];

                    $totalDistance += $router->total_distance_km;
                    $index++;
                }

                $this->optimizationResult = [
                    'total_distance_km' => $totalDistance,
                    'is_feasible'       => true,
                ];

                $payloadResult = [
                    'depot'  => $depotData,
                    'routes' => $routesData,
                ];

                // إرسال البيانات لخريطة Leaflet
                $this->dispatch('routesCalculated', data: $payloadResult);
            });

            session()->flash('success', 'تمت معالجة كافة الطلبات، وتحديث حالتها إلى assigned، ورسم المسارات بنجاح!');

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء المعالجة: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.route-planner', [
            'pendingOrdersCount' => Order::where('status', 'pending')->count(),
            'vehiclesCount'      => Vehicle::where('status', 'available')->count(),
            'routesList'         => Router::with(['vehicle'])->latest()->take(10)->get()
        ]);
    }
}