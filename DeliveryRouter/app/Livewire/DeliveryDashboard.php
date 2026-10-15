<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Vehicle;

class DeliveryDashboard extends Component
{
    // حقول الطلب الجديد
    public $customer_name;
    public $customer_phone;
    public $delivery_address;
    public $weight_kg = 1.0; 
    public $latitude = 34.8886; 
    public $longitude = 35.8866;

    // حقول شاشة إدارة الشاحنة والأسطول (مع اسم السائق)
    public $driver_name;
    public $plate_number;
    public $truck_capacity = 500;

    protected $rules = [
        'customer_name'    => 'required|string|max:255',
        'customer_phone'   => 'nullable|string|max:20',
        'delivery_address' => 'required|string|max:255',
        'weight_kg'        => 'required|numeric|min:0.1',
        'latitude'         => 'required|numeric',
        'longitude'        => 'required|numeric',
    ];

    protected $listeners = ['locationSelected' => 'updateCoordinates'];

    public function updateCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function createOrder()
    {
        $this->validate([
            'customer_name'    => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'weight_kg'        => 'required|numeric|min:0.1',
        ]);

        Order::create([
            'customer_name'    => $this->customer_name,
            'customer_phone'   => $this->customer_phone,
            'delivery_address' => $this->delivery_address,
            'weight_kg'        => $this->weight_kg,
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'status'           => 'pending',
        ]);

        $this->reset(['customer_name', 'customer_phone', 'delivery_address', 'weight_kg']);
        session()->flash('success_order', 'تم حفظ الطلب بنجاح وتثبيته على الخريطة!');
    }

    public function render()
    {
        return view('livewire.delivery-dashboard', [
            'recentOrders'  => Order::latest()->take(5)->get(),
            'vehiclesList'  => Vehicle::latest()->take(5)->get(),
            'pendingCount'  => Order::where('status', 'pending')->count(),
            'vehiclesCount' => Vehicle::where('status', 'available')->count(),
        ]);
    }
}