@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 4rem auto; padding: 0 1rem;">
    <h1 style="color: var(--text-primary); margin-bottom: 2rem;">My Orders</h1>

    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--error); color: var(--error); padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; background: var(--surface); border-radius: 12px; border: 1px solid var(--border);">
            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 1.5rem;">You haven't placed any orders yet.</p>
            <a href="/" class="btn">Start Shopping</a>
        </div>
    @else
        <div style="display: grid; gap: 1.5rem;">
            @foreach($orders as $order)
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                        <div>
                            <span style="color: var(--text-secondary); font-size: 0.875rem;">Order #{{ $order->id }}</span>
                            <h3 style="color: var(--text-primary); margin-top: 0.25rem;">{{ __('Total') }}: {{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</h3>
                            <p style="color: var(--text-secondary); font-size:0.875rem; margin-top: 0.25rem;">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.875rem; font-weight: 500; 
                                {{ $order->payment_status === 'paid' ? 'background: rgba(16, 185, 129, 0.1); color: var(--success);' : 
                                  ($order->payment_status === 'failed' ? 'background: rgba(239, 68, 68, 0.1); color: var(--error);' : 
                                  'background: rgba(245, 158, 11, 0.1); color: #f59e0b;') }}">
                                Payment: {{ ucfirst($order->payment_status) }}
                            </span>
                            @if($order->payment_status === 'pending')
                                <div style="margin-top: 0.5rem;">
                                    <a href="{{ route('payment.gateway', $order->id) }}" class="btn" style="padding: 0.2rem 0.8rem; font-size: 0.875rem;">Pay Now</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1rem;">Shipments ({{ $order->subOrders->count() }})</h4>
                        <div style="display: grid; gap: 1rem;">
                            @foreach($order->subOrders as $subOrder)
                                <div style="border: 1px solid var(--border); border-radius: 8px; padding: 1rem; background: var(--background);">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <span style="color: var(--text-primary); font-weight: 500;">Sold by: {{ $subOrder->store->name }}</span>
                                        <span style="font-size: 0.875rem; color: 
                                             {{ $subOrder->status === 'delivered' ? 'var(--success)' : 
                                               ($subOrder->status === 'cancelled' || $subOrder->status === 'returned' ? 'var(--error)' : 'var(--primary)') }}">
                                             {{ __('Status') }}: {{ ucfirst($subOrder->status) }}
                                         </span>
                                         @if($subOrder->tracking_number)
                                             <a href="{{ route('tracking.show', $subOrder->tracking_number) }}" style="font-size: 0.75rem; color: var(--primary); text-decoration: underline; margin-left: 10px;">Track Shipment</a>
                                         @endif
                                    </div>
                                    <ul style="list-style: none; padding: 0; margin-bottom: 1rem;">
                                        @foreach($subOrder->items as $item)
                                            <li style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px dashed var(--border);">
                                                 <span style="color: var(--text-secondary);">{{ $item->product->name }} x {{ $item->quantity }}</span>
                                                 <span style="color: var(--text-primary);">{{ \App\Helpers\CurrencyHelper::format($item->price * $item->quantity) }}</span>
                                             </li>
                                        @endforeach
                                    </ul>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                         <strong style="color: var(--text-primary);">{{ __('Subtotal') }}: {{ \App\Helpers\CurrencyHelper::format($subOrder->total_amount) }}</strong>
                                        
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ route('chat.start', ['seller_id' => $subOrder->store->user_id, 'order_id' => $order->id]) }}" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: transparent; border: 1px solid var(--primary); color: var(--primary); text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center;">Inquire</a>
                                            
                                            @if($order->payment_status === 'paid')
                                                @if($subOrder->status === 'pending')
                                                    <form method="POST" action="{{ route('orders.status.update', $subOrder->id) }}" style="margin: 0;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: transparent; border: 1px solid var(--error); color: var(--error);">Cancel Item</button>
                                                    </form>
                                                @elseif($subOrder->status === 'delivered')
                                                    <form method="POST" action="{{ route('orders.status.update', $subOrder->id) }}" style="margin: 0;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="returned">
                                                        <button type="submit" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; background: transparent; border: 1px solid var(--warning); color: var(--warning);">Return Item</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
