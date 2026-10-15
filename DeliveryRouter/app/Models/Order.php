<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'delivery_address',
        'latitude',
        'longitude',
        'weight_kg',
        'status',
    ];

    public function routeStop()
    {
        return $this->hasOne(RouterStop::class);
    }
}