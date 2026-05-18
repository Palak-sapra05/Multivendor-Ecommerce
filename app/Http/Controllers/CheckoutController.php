<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function showCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect('/cart');

        return view('checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return $request->ajax() 
                ? response()->json(['error' => 'Cart is empty'], 400) 
                : redirect('/');
        }

        try {
            $cartItems = collect($cart)->values()->toArray();
            $order = $this->orderService->createOrderFromCart(Auth::user(), $cartItems);
            
            // Generate demo tracking info for each sub-order
            foreach($order->subOrders as $subOrder) {
                $subOrder->update([
                    'tracking_number' => 'NEX' . strtoupper(substr(md5(uniqid()), 0, 10)),
                    'carrier' => 'NexMart Express',
                    'estimated_delivery_at' => now()->addDays(rand(3, 7))
                ]);
            }

            // Clear cart
            session()->forget('cart');
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect' => route('orders.index')
                ]);
            }

            return redirect()->route('orders.index')->with('success', 'Order placed successfully! You can pay for your order here.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors(['msg' => 'Error placing order: ' . $e->getMessage()]);
        }
    }
}
