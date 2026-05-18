<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\SubOrder;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::with(['subOrders.store', 'subOrders.items.product'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['subOrders.store', 'subOrders.items.product'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:cancelled,returned'
        ]);

        $subOrder = SubOrder::whereHas('order', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        if ($request->status === 'cancelled' && $subOrder->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        if ($request->status === 'returned' && $subOrder->status !== 'delivered') {
            return back()->with('error', 'Only delivered orders can be returned.');
        }

        $subOrder->status = $request->status;
        $subOrder->save();

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status));
    }
}
