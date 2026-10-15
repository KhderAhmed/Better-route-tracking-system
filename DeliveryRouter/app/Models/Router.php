<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'vehicle_id',
        'total_distance_km',
        'total_weight_kg',
        'status',
        'calculated_at',
    ];

    protected $table = 'routers';

    protected $guarded = [];

    public function stops()
    {
        return $this->hasMany(RouterStop::class, 'route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class, 'depot_id');
    }
}
