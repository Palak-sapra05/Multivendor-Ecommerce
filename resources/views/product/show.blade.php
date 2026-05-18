@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 2rem auto;">
    <!-- Main Product Grid -->
    <div style="padding: 2rem; background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 2rem;">
        <!-- Product Image Carousel -->
        <div style="border-radius: 12px; overflow: hidden; background: var(--bg-surface-hover); height: 400px; position: relative;">
            <img id="mainImage" src="{{ !empty($product->images) && is_array($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://picsum.photos/seed/'.$product->id.'/800/600' }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 0.2s ease;">
            
            <button onclick="prevImage()" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: background 0.3s;" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">&#10094;</button>
            <button onclick="nextImage()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: background 0.3s;" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">&#10095;</button>
            
            <div id="carousel-indicators" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px;"></div>
        </div>
        
        <!-- Product Details -->
            <div style="text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">
                {{ $product->brand ?? 'NexMart Exclusive' }}
            </div>
            <h1 style="color: var(--text-primary); font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.2;">{{ $product->name }}</h1>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 8px; width: fit-content;">
                <div style="display: flex; align-items: center; gap: 4px; font-weight: 700;">
                    <span>{{ number_format($product->average_rating, 1) }}</span>
                    <svg viewBox="0 0 20 20" style="color: #4ade80; width: 16px; height: 16px; fill: currentColor;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <div style="width: 1px; height: 15px; background: var(--border);"></div>
                <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-secondary);">{{ $product->review_count }} Ratings</span>
            </div>

            <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; display: flex; align-items: baseline; gap: 0.5rem;">
                {{ \App\Helpers\CurrencyHelper::format($product->price) }}
                <span style="font-size: 1.1rem; color: #ff905a; font-weight: 600;">(Special Price)</span>
            </div>
            <div style="color: #03a685; font-weight: 700; font-size: 0.9rem; margin-bottom: 2rem;">inclusive of all taxes</div>

            @if(!empty($product->sizes) && count($product->sizes) > 0)
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="font-weight: 700; text-transform: uppercase; font-size: 0.9rem;">Select Size</h4>
                        <span style="color: var(--primary); font-weight: 700; font-size: 0.9rem; cursor: pointer;">SIZE CHART ></span>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @foreach($product->sizes as $size)
                            <div class="size-option" onclick="selectSize(this)" style="width: 45px; height: 45px; border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 700; font-size: 0.85rem; transition: all 0.2s;">
                                {{ $size }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($product->colors) && count($product->colors) > 0)
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-weight: 700; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 1rem;">Colors</h4>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @foreach($product->colors as $color)
                            <div class="color-option" onclick="selectColor(this)" style="padding: 0.5rem 1.25rem; border: 1px solid var(--border); border-radius: 100px; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: all 0.2s;">
                                {{ $color }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 2rem;">
                Sold by <span style="color: var(--primary); font-weight: 700;">{{ $product->store->name }}</span>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <form method="POST" action="{{ route('cart.add') }}" style="flex-grow: 1;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 0.8rem; background: var(--primary); border: none;">
                        ADD TO BAG
                    </button>
                </form>
                
                <form method="POST" action="{{ in_array($product->id, session('wishlist', [])) ? route('wishlist.remove') : route('wishlist.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" style="padding: 1rem 1.5rem; border: 1px solid var(--border); background: var(--bg-surface); border-radius: 8px; color: var(--text-primary); cursor: pointer; display: flex; align-items: center; gap: 0.8rem; font-weight: 700; transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="{{ in_array($product->id, session('wishlist', [])) ? 'var(--primary)' : 'none' }}" stroke="{{ in_array($product->id, session('wishlist', [])) ? 'var(--primary)' : 'currentColor' }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <div style="margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <a href="{{ route('chat.start', ['seller_id' => $product->store->user_id]) }}" style="text-decoration: none; display: flex; align-items: center; gap: 0.8rem; color: var(--text-primary); font-weight: 600; padding: 0.5rem 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Chat with Seller
                </a>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div style="padding: 2rem; background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
            <h3 style="font-size: 1.5rem; font-weight: 800;">Customer Reviews</h3>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-weight: 700; font-size: 1.2rem;">{{ number_format($product->average_rating, 1) }} / 5.0</span>
            </div>
        </div>

        @auth
            <form action="{{ route('reviews.store') }}" method="POST" style="background: var(--bg-base); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <h4 style="margin-bottom: 1rem; font-weight: 700;">Rate this product</h4>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div id="star-rating-container" style="display: flex; gap: 5px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="star-btn" style="cursor: pointer;">
                                <input type="radio" name="rating" value="{{ $i }}" style="display: none;" {{ $i == 5 ? 'checked' : '' }}>
                                <svg class="star-icon" data-index="{{ $i }}" viewBox="0 0 20 20" style="width: 28px; height: 28px; fill: {{ $i <= 5 ? '#FBBF24' : '#D1D5DB' }}; transition: all 0.2s;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </label>
                        @endfor
                    </div>

                    <textarea name="comment" placeholder="Write your thoughts about this product..." style="width: 100%; padding: 1rem; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-primary); font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                    
                    <button type="submit" class="btn" style="align-self: flex-start; padding: 0.75rem 2rem;">Submit Review</button>
                </div>
            </form>
        @else
            <div style="text-align: center; padding: 2rem; background: var(--bg-base); border-radius: 12px; margin-bottom: 2rem;">
                <p>Please <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">login</a> to leave a review.</p>
            </div>
        @endauth

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @forelse($product->reviews()->with('user')->latest()->get() as $review)
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <div style="font-weight: 700;">{{ $review->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="rating-stars" style="margin-bottom: 0.5rem;">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 20 20" style="color: {{ $i <= $review->rating ? '#FBBF24' : '#D1D5DB' }}; width: 14px; height: 14px;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p style="color: var(--text-secondary); line-height: 1.6;">{{ $review->comment }}</p>
                </div>
            @empty
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No reviews yet. Be the first to review this product!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image Carousel Logic
    const images = {!! json_encode(!empty($product->images) && is_array($product->images) && count($product->images) > 0 ? $product->images : ["https://picsum.photos/seed/".$product->id."/800/600"]) !!};
    let currentIndex = 0;
    const indicatorsContainer = document.getElementById('carousel-indicators');
    
    if (images.length > 1) {
        images.forEach((img, i) => {
            const dot = document.createElement('span');
            dot.id = 'dot' + i;
            dot.style = `height: 10px; width: 10px; background-color: white; border-radius: 50%; display: inline-block; cursor: pointer; transition: opacity 0.3s; opacity: ${i === 0 ? '1' : '0.5'}`;
            dot.onclick = () => setImage(i);
            indicatorsContainer.appendChild(dot);
        });
    }

    function updateCarousel() {
        const imgElement = document.getElementById('mainImage');
        imgElement.style.opacity = 0;
        setTimeout(() => {
            imgElement.src = images[currentIndex];
            imgElement.style.opacity = 1;
        }, 200);
        
        images.forEach((_, i) => {
            const dot = document.getElementById('dot' + i);
            if (dot) dot.style.opacity = (i === currentIndex) ? '1' : '0.5';
        });
    }
    
    function nextImage() { currentIndex = (currentIndex + 1) % images.length; updateCarousel(); }
    function prevImage() { currentIndex = (currentIndex - 1 + images.length) % images.length; updateCarousel(); }
    function setImage(index) { currentIndex = index; updateCarousel(); }

    // Star Rating Interactivity
    const starContainer = document.getElementById('star-rating-container');
    if (starContainer) {
        const stars = starContainer.querySelectorAll('.star-icon');
        const inputs = starContainer.querySelectorAll('input');

        function updateStars(rating) {
            stars.forEach((star, index) => {
                star.style.fill = (index < rating) ? '#FBBF24' : '#D1D5DB';
                star.style.transform = (index < rating) ? 'scale(1.1)' : 'scale(1)';
            });
        }

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => updateStars(index + 1));
            star.addEventListener('mouseout', () => {
                const checkedInput = starContainer.querySelector('input:checked');
                updateStars(checkedInput ? checkedInput.value : 0);
            });
            star.parentElement.addEventListener('click', () => {
                inputs[index].checked = true;
                updateStars(index + 1);
            });
        });
    }
    // Size and Color Selection
    function selectSize(element) {
        document.querySelectorAll('.size-option').forEach(el => {
            el.style.borderColor = 'var(--border)';
            el.style.color = 'var(--text-primary)';
            el.style.background = 'transparent';
        });
        element.style.borderColor = '#B1B2FF';
        element.style.color = '#B1B2FF';
        element.style.background = 'rgba(177, 178, 255, 0.1)';
    }

    function selectColor(element) {
        document.querySelectorAll('.color-option').forEach(el => {
            el.style.borderColor = 'var(--border)';
            el.style.color = 'var(--text-primary)';
            el.style.background = 'transparent';
        });
        element.style.borderColor = '#B1B2FF';
        element.style.color = '#B1B2FF';
        element.style.background = 'rgba(177, 178, 255, 0.1)';
    }
</script>
@endsection
