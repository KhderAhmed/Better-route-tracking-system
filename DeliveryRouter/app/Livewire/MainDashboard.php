<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\Router;
use App\Models\Depot;

class MainDashboard extends Component
{
    public function render()
    {
        return view('livewire.main-dashboard', [
            // الأسطول
            'availableVehicles' => Vehicle::where('status', 'available')->count(),
            'onRouteVehicles' => Vehicle::where('status', 'on_route')->count(),
            'maintenanceVehicles' => Vehicle::where('status', 'maintenance')->count(),

            // الطلبات
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'assignedOrders' => Order::where('status', 'assigned')->count(),
            'deliveredOrders' => Order::where('status', 'delivered')->count(),
            'cancelledOrders' => Order::where('status', 'cancelled')->count(),

            // المسارات
            'calculatedRoutes' => Router::where('status', 'calculated')->count(),
            'completedRoutes' => Router::where('status', 'completed')->count(),
            'pendingRoutes' => Router::where('status', 'pending')->count(),

            // المستودعات
            'activeDepots' => Depot::where('is_active', true)->count(),
            'inactiveDepots' => Depot::where('is_active', false)->count(),
        ]);
    }
}