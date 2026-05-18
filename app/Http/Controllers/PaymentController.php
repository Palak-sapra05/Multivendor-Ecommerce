<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function gateway(Order $order)
    {
        // Ensure the order belongs to the user and is pending payment
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            return redirect('/')->with('error', 'Invalid order or already paid.');
        }

        $order->load('subOrders.items.product', 'subOrders.store');

        return view('payment.gateway', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            return $request->ajax()
                ? response()->json(['error' => 'Invalid order or already paid.'], 400)
                : redirect('/')->with('error', 'Invalid order or already paid.');
        }

        try {
            // Mark order as paid
            $order->update([
                'payment_status' => 'paid',
            ]);

            // Update sub_orders status and generate tracking
            foreach ($order->subOrders as $subOrder) {
                $subOrder->update([
                    'status' => 'shipped',
                    'tracking_number' => $subOrder->tracking_number ?: 'NEX' . strtoupper(substr(md5(uniqid()), 0, 10)),
                    'carrier' => $subOrder->carrier ?: 'NexMart Express',
                    'estimated_delivery_at' => $subOrder->estimated_delivery_at ?: now()->addDays(rand(3, 7)),
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect' => route('orders.index')
                ]);
            }

            return redirect()->route('orders.index')->with('success', 'Payment successful! Order #' . $order->id . ' is now being processed.');

        } catch (\Exception $e) {
            Log::error('Payment Processing Failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->route('orders.index')->with('error', 'Payment processing failed.');
        }
    }
}
