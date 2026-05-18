<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'store_id', 
        'total_amount', 
        'commission_amount', 
        'seller_earning', 
        'status',
        'tracking_number',
        'carrier',
        'estimated_delivery_at',
        'current_location'
    ];

    protected $casts = [
        'estimated_delivery_at' => 'datetime',
        'current_location' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
