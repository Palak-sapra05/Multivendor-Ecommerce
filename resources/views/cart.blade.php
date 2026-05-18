@extends('layouts.app')

@section('content')
    <h2>Your Cart</h2>

    @if(empty($cart))
        <div class="cart-widget">
            <p style="text-align:center">Your cart is empty.</p>
            <div style="text-align:center; margin-top:2rem;">
                <a href="/" class="btn">Shop Now</a>
            </div>
        </div>
    @else
        <div class="cart-widget">
            @php $total = 0; @endphp
            @foreach($cart as $id => $item)
                @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                <div class="cart-item">
                    <div>
                        <div class="card-title">{{ $item['title'] }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary)">From: {{ $item['store_name'] }}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap: 2rem;">
                        <div>{{ \App\Helpers\CurrencyHelper::format($item['price']) }}</div>
                        
                        <form method="POST" action="{{ route('cart.update') }}" style="display:flex; align-items:center; gap:0.5rem">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width: 60px; padding: 0.25rem; border-radius:4px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary)">
                            <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem">Update</button>
                        </form>

                        <div style="font-weight:bold; width:80px; text-align:right">{{ \App\Helpers\CurrencyHelper::format($itemTotal) }}</div>
                        
                        <form method="POST" action="{{ route('cart.remove') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">{{ __('Grand Total') }}: {{ \App\Helpers\CurrencyHelper::format($total) }}</div>
                <a href="{{ route('checkout.form') }}" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem">{{ __('Proceed to Checkout') }}</a>
            </div>
        </div>
    @endif
@endsection
