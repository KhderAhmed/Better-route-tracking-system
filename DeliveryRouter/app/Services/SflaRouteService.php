<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SflaRouteService
{
    protected string $baseUrl;

    public function __construct()
    {
        // رابط سيرفر FastAPI
        $this->baseUrl = config('services.sfla.url', 'http://127.0.0.1:8000');
    }

    /**
     * إرسال بيانات التوصيل لمحرك الذكاء الاصطناعي للحصول على المسارات المثلى
     */
    public function optimizeRoutes($depot, $orders, int $numVehicles, float $vehicleCapacity)
    {
        $payload = [
            'depot' => [
                'id' => $depot->id,
                'lat' => (float)$depot->latitude,
                'lng' => (float)$depot->longitude,
                'weight_kg' => 0.0,
            ],
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'lat' => (float)$order->latitude,
                    'lng' => (float)$order->longitude,
                    'weight_kg' => (float)$order->weight_kg,
                ];
            })->toArray(),
            'num_vehicles' => $numVehicles,
            'vehicle_capacity' => $vehicleCapacity,
            'pop_size' => 30,
            'max_iter' => 25
        ];

        $response = Http::timeout(60)->post("{$this->baseUrl}/api/v1/optimize-routes", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("SFLA Engine Error: " . $response->body());
    }
}
