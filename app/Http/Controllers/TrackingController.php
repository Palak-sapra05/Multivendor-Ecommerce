<?php

namespace App\Http\Controllers;

use App\Models\SubOrder;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show($tracking_number)
    {
        $subOrder = SubOrder::where('tracking_number', $tracking_number)
            ->with(['order.user', 'store', 'items.product'])
            ->firstOrFail();

        // Simulate some live tracking data if it's missing
        if (!$subOrder->current_location) {
            $subOrder->current_location = [
                'lat' => 28.6139 + (rand(-100, 100) / 1000),
                'lng' => 77.2090 + (rand(-100, 100) / 1000),
                'address' => 'In Transit - Near Delhi, India'
            ];
        }

        if (!$subOrder->estimated_delivery_at) {
            $subOrder->estimated_delivery_at = now()->addDays(2);
        }

        return view('tracking.show', compact('subOrder'));
    }
}
