<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Router;
use App\Models\Order;
use App\Models\Vehicle;

class ReportsAnalytics extends Component
{
    public $dateRange = 'all';

    public function render()
    {
        return view('livewire.reports-analytics', [
            'totalKm'          => Router::sum('total_distance_km'),
            'completedRoutes'  => Router::where('status', 'completed')->count(),
            'totalDelivered'   => Order::where('status', 'delivered')->count(),
            'activeFleetCount' => Vehicle::where('status', 'on_route')->count(),
            'recentRoutes'     => Router::with(['vehicle', 'depot'])->latest()->take(5)->get(),
        ]);
    }
}