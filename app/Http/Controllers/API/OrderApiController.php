<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderApiController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(Request $request)
    {
        // Validate request
        $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
        ]);

        // Fake auth user for testing, in real app use $request->user()
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Demo Customer', 'password' => bcrypt('password'), 'role' => 'customer']
        );

        try {
            $order = $this->orderService->createOrderFromCart($user, $request->input('cart_items'));
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('subOrders.items.product')
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
