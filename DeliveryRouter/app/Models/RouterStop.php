<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterStop extends Model
{
    use HasFactory;

    protected $table = 'router_stops';

    protected $fillable = [
        'route_id',
        'order_id',
        'stop_order',
        'is_completed',
    ];

    // ربط الموديل بموديل Router مع تحديد المفتاح الأجنبي الصحيح route_id
    public function route()
    {
        return $this->belongsTo(Router::class, 'route_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}