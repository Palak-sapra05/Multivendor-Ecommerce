<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SubOrder;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function createOrderFromCart($user, $cartItems)
    {
        // $cartItems should be a collection of objects/arrays with:
        // product_id, quantity. We assume we fetch the models.

        // Load the actual product models
        $productIds = collect($cartItems)->pluck('product_id')->toArray();
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        $enrichedCartItems = collect($cartItems)->map(function($item) use ($products) {
            $product = $products->get($item['product_id']);
            return (object) [
                'product' => $product,
                'quantity' => $item['quantity'],
            ];
        });

        // 1. Group cart items by the product's store_id
        $groupedItems = $enrichedCartItems->groupBy('product.store_id');
        $grandTotal = $enrichedCartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // 2. Use DB Transaction to ensure data integrity
        return DB::transaction(function () use ($user, $groupedItems, $grandTotal) {
            
            // Create Master Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $grandTotal,
                'payment_status' => 'pending'
            ]);

            // Create SubOrders per Seller
            foreach ($groupedItems as $storeId => $items) {
                $storeTotal = $items->sum(function($item) {
                    return $item->product->price * $item->quantity;
                });

                // Calculate Commission
                $commission = $this->commissionService->calculate($storeTotal);
                $sellerEarning = $storeTotal - $commission;

                // Create SubOrder
                $subOrder = SubOrder::create([
                    'order_id' => $order->id,
                    'store_id' => $storeId,
                    'total_amount' => $storeTotal,
                    'commission_amount' => $commission,
                    'seller_earning' => $sellerEarning,
                    'status' => 'pending'
                ]);

                // Create Order Items
                foreach ($items as $item) {
                    OrderItem::create([
                        'sub_order_id' => $subOrder->id,
                        'product_id' => $item->product->id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);

                    // Decrement Stock
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            return $order;
        });
    }
}
