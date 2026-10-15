<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OsrmRoutingService
{
    // السيرفر العام الافتراضي لـ OSRM (يمكنك استبداله بسيرفر محلي Docker لدعم سوريا دون قيود)
protected string $baseUrl = 'https://router.project-osrm.org/table/v1/driving/';


    /**
     * جلب مصفوفة الأزمنة (بالدقائق) والمسافات (بالكيلومترات) بين مجموعة من النقاط.
     *
     * @param array $locations قائمة من النقاط بصيغة [['lat' => ..., 'lng' => ...], ...]
     * @return array [ 'durations' => [ [m11, m12], ... ], 'distances' => [ [d11, d12], ... ] ]
     */
    public function getDistanceMatrix(array $locations): array
    {
        if (count($locations) < 2) {
            return ['durations' => [], 'distances' => []];
        }

        // تحويل الإحداثيات إلى الصيغة المطلوبة لـ OSRM: (lng,lat;lng,lat;...)
        $coordinatesString = collect($locations)
            ->map(fn($point) => "{$point['lng']},{$point['lat']}")
            ->implode(';');

        try {
            $response = Http::timeout(5)->get($this->baseUrl . $coordinatesString, [
                'annotations' => 'duration,distance',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // تحويل الأزمنة من ثوانٍ إلى دقائق
                $durationsInMinutes = collect($data['durations'])
                    ->map(fn($row) => collect($row)->map(fn($sec) => round($sec / 60, 2))->toArray())
                    ->toArray();

                // تحويل المسافات من أمتار إلى كيلومترات
                $distancesInKm = collect($data['distances'])
                    ->map(fn($row) => collect($row)->map(fn($meters) => round($meters / 1000, 2))->toArray())
                    ->toArray();

                return [
                    'durations' => $durationsInMinutes,
                    'distances' => $distancesInKm,
                ];
            }
        } catch (\Exception $e) {
            Log::error('OSRM API Error: ' . $e->getMessage());
        }

        // fallback: استخدام Haversine إذا تعثر الاتصال بالسيرفر
        return $this->fallbackHaversineMatrix($locations);
    }

    /**
     * حساب مصفوفة إحتياطية بناءً على Haversine بمتوسط سرعة 40 كم/سا داخل المدينة
     */
    protected function fallbackHaversineMatrix(array $locations): array
    {
        $count = count($locations);
        $durations = [];
        $distances = [];

        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {
                if ($i === $j) {
                    $distances[$i][$j] = 0;
                    $durations[$i][$j] = 0;
                    continue;
                }

                $dist = $this->haversineDistance(
                    $locations[$i]['lat'],
                    $locations[$i]['lng'],
                    $locations[$j]['lat'],
                    $locations[$j]['lng']
                );

                $distances[$i][$j] = round($dist, 2);
                $durations[$i][$j] = round(($dist / 40) * 60, 2); // 40 km/h average
            }
        }

        return ['durations' => $durations, 'distances' => $distances];
    }

    protected function haversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
