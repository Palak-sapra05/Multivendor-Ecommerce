@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
        <div style="margin-bottom: 2rem;">
            <h1
                style="color: var(--text-primary); font-size: 1.8rem; font-weight: 800; display: flex; align-items: center; gap: 1rem;">
                My Wishlist
                <span style="font-weight: 400; color: var(--text-secondary); font-size: 1.1rem;">({{ count($products) }}
                    Items)</span>
            </h1>
        </div>

        @if(count($products) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 2rem;">
                @foreach($products as $product)
                    <div style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; position: relative;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <!-- Remove Button -->
                        <form action="{{ route('wishlist.remove') }}" method="POST"
                            style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                style="background: rgba(255,255,255,0.9); border: 1px solid #ddd; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666; transition: all 0.2s;"
                                onmouseover="this.style.background='#B1B2FF'; this.style.color='white'; this.style.borderColor='#B1B2FF'"
                                onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.color='#666'; this.style.borderColor='#ddd'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </form>

                        <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit;">
                            <div style="height: 280px; overflow: hidden; background: var(--bg-surface-hover);">
                                <img src="{{ !empty($product->images) && is_array($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://picsum.photos/seed/' . $product->id . '/400/500' }}"
                                    alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="padding: 1rem;">
                                <h3
                                    style="font-size: 1rem; font-weight: 700; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-primary);">
                                    {{ $product->name }}</h3>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                                    {{ $product->store->name }}</div>
                                <div style="font-weight: 800; color: var(--text-primary); margin-bottom: 1rem;">
                                    ₹{{ number_format($product->price, 2) }}</div>
                            </div>
                        </a>

                        <div style="padding: 0 1rem 1rem 1rem;">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    style="width: 100%; padding: 0.75rem; background: transparent; border: 1px solid var(--primary); color: var(--primary); border-radius: 4px; font-weight: 700; cursor: pointer; transition: all 0.2s; text-transform: uppercase; font-size: 0.85rem;"
                                    onmouseover="this.style.background='#B1B2FF'; this.style.color='white'"
                                    onmouseout="this.style.background='transparent'; this.style.color='#B1B2FF'">
                                    Move to Bag
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 5rem 0;">
                <div style="font-size: 5rem; color: var(--border); margin-bottom: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </div>
                <h2 style="color: var(--text-primary); margin-bottom: 1rem;">Your wishlist is empty</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Add items that you like to your wishlist. They
                    will show up here.</p>
                <a href="/" class="btn" style="padding: 0.75rem 2.5rem; text-decoration: none;">Continue Shopping</a>
            </div>
        @endif
    </div>
@endsection