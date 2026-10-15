<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Depot;

class DepotsManager extends Component
{
    public $name;
    public $address;
    public $latitude = 34.8886;  // طرطوس افتراضياً
    public $longitude = 35.8866;
    public $is_active = true;

    protected $listeners = ['locationSelected' => 'updateCoordinates'];

    public function updateCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function saveDepot()
    {
        $this->validate([
            'name'      => 'required|string|max:255',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Depot::create([
            'name'      => $this->name,
            'address'   => $this->address,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'address']);
        session()->flash('success', 'تمت إضافة المستودع الرئيسي وتثبيت إحداثياته بنجاح!');
    }

    public function render()
    {
        return view('livewire.depots-manager', [
            'depots' => Depot::latest()->get()
        ]);
    }
}