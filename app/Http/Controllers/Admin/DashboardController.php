<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use App\Models\User;
use App\Models\Store;

class DashboardController extends Controller
{
    public function index()
    {
        // Total platform revenue (admin's cut only)
        $totalCommission = SubOrder::sum('commission_amount');
        
        // Activity stats
        $totalOrders = SubOrder::count();
        $totalUsers = User::count();
        $totalStores = Store::count();
        
        return response()->json([
            'total_commission' => $totalCommission,
            'total_orders' => $totalOrders,
            'total_users' => $totalUsers,
            'total_stores' => $totalStores
        ]);
    }
}
