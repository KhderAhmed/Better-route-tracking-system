<?php
namespace App\Services;

use App\Models\Order;
use App\Services\OsrmRoutingService;

class SflaOptimizer
{
    protected OsrmRoutingService $osrmService;

    public function __construct(OsrmRoutingService $osrmService)
    {
        $this->osrmService = $osrmService;
    }

    public function optimizeRoutes($depot, $orders)
    {
        // 1. تجميع النقاط (المستودع بالدليل 0 + باقي الطلبات)
        $locations = collect([$depot])
            ->concat($orders)
            ->map(fn($item) => [
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
            ])
            ->toArray();

        // 2. جلب مصفوفة الأزمنة الحقيقية من OSRM
        $matrixData = $this->osrmService->getDistanceMatrix($locations);
        $timeMatrix = $matrixData['durations']; // مصفوفة زمن القيادة بالدقائق

        // 3. تقييم لياقة الحل (Fitness Function) بـ SFLA باستخدام وقت القيادة الفعلي
        // $travelTime = $timeMatrix[$i][$j];
        
        return $this->runShuffledFrogLeapingAlgorithm($timeMatrix, $orders);
    }

    protected function runShuffledFrogLeapingAlgorithm(array $timeMatrix, $orders)
    {
        // تنفيذ خطوات SFLA مع اعتماد $timeMatrix لتوجيه الضفادع نحو تقليل الزمن الكلي للتوصيل
    }
}