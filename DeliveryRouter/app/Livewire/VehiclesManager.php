<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;

class VehiclesManager extends Component
{
    public $driver_name;
    public $plate_number;
    public $capacity_kg = 500;
    public $status = 'available';

    public function saveVehicle()
    {
        $this->validate([
            'driver_name'  => 'required|string|max:255',
            'plate_number' => 'required|string|max:50|unique:vehicles,plate_number',
            'capacity_kg'  => 'required|numeric|min:10',
            'status'       => 'required|in:available,on_route,maintenance',
        ]);

        Vehicle::create([
            'driver_name'  => $this->driver_name,
            'plate_number' => $this->plate_number,
            'capacity_kg'  => $this->capacity_kg,
            'status'       => $this->status,
        ]);

        $this->reset(['driver_name', 'plate_number', 'capacity_kg', 'status']);
        session()->flash('success', 'تم حفظ وتحديث بيانات الشاحنة بنجاح!');
    }

    public function deleteVehicle($id)
    {
        Vehicle::find($id)?->delete();
        session()->flash('success', 'تم حذف الشاحنة من الأسطول بنجاح.');
    }

    public function render()
    {
        return view('livewire.vehicles-manager', [
            'vehicles' => Vehicle::latest()->paginate(10)
        ]);
    }
}