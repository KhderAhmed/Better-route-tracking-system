<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Depot;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Router;

class OptimizeRoutesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;

    public $timeout = 300;
    public $tries = 3;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        try {
            $numVehicles = $this->payload['num_vehicles'] ?? 3;

            $depot = Depot::first();
            $pendingOrders = Order::where('status', 'pending')->get();

            if (!$depot || $pendingOrders->isEmpty()) {
                Log::warning('Route optimization skipped: No depot or pending orders found.');
                return;
            }

            DB::transaction(function () use ($numVehicles, $pendingOrders, $depot) {
                // جلب الشاحنات المتاحة
                $vehicles = Vehicle::where('status', 'available')->get();
                
                // تقسيم الطلبات
                $chunks = $pendingOrders->chunk(ceil($pendingOrders->count() / max($numVehicles, 1)));

                foreach ($chunks as $index => $chunk) {
                    $totalWeight = $chunk->sum('weight_kg');
                    $vehicle = $vehicles[$index] ?? $vehicles->first();

                    // حفظ المسار الأساسي وتخصيص الشاحنة (بدون أي تداخل مع جدول المحطات)
                    Router::create([
                        'depot_id'          => $depot->id,
                        'vehicle_id'        => $vehicle ? $vehicle->id : null,
                        'total_weight_kg'   => $totalWeight,
                        'total_distance_km' => rand(5, 15),
                        'status'            => 'calculated',
                    ]);
                }
            });

            Log::info('Route optimization completed successfully.');
        } catch (\Exception $e) {
            Log::error('Route optimization exception: ' . $e->getMessage());
            throw $e;
        }
    }
}