<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\SubOrder;

class DashboardController extends Controller
{
    public function index(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);

        // Seller earnings
        $totalEarnings = SubOrder::where('store_id', $store->id)->sum('seller_earning');
        
        // Orders specific to this seller
        $recentOrders = SubOrder::with('items')->where('store_id', $store->id)->orderBy('created_at', 'desc')->take(10)->get();
        
        return response()->json([
            'store_name' => $store->name,
            'total_earnings' => $totalEarnings,
            'recent_orders' => $recentOrders
        ]);
    }
}
