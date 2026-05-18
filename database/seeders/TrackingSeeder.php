<?php

namespace Database\Seeders;

use App\Models\SubOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrackingSeeder extends Seeder
{
    public function run(): void
    {
        $subOrders = SubOrder::whereNull('tracking_number')->get();
        foreach ($subOrders as $subOrder) {
            $subOrder->update([
                'tracking_number' => 'NEX' . strtoupper(Str::random(10)),
                'carrier' => 'NexMart Express',
                'estimated_delivery_at' => now()->addDays(rand(1, 5)),
                'current_location' => [
                    'lat' => 28.6139 + (rand(-100, 100) / 1000),
                    'lng' => 77.2090 + (rand(-100, 100) / 1000),
                    'address' => 'In Transit - Near Delhi, India'
                ]
            ]);
        }
    }
}
