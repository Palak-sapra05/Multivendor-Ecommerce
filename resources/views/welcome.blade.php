@extends('layouts.app')

@section('content')
    <style>
        .hero-section-wrapper {
            width: 95%;
            max-width: 1700px;
            margin: 0.5rem auto 3rem;
            position: relative;
            perspective: 1000px;
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4);
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            visibility: hidden;
            z-index: 1;
        }

        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 2;
        }

        .premium-hero {
            position: relative;
            width: 100%;
            height: 100%;
            background: #000;
            display: flex;
            align-items: center;
        }

        .hero-slide.active .modern-glass-card {
            animation: slideInUp 1.2s cubic-bezier(0.2, 1, 0.2, 1) forwards;
        }

        .hero-slide.active .product-image-premium {
            animation: floatProduct 8s ease-in-out infinite, fadeInRight 1.5s ease-out forwards;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px) translateY(-50%);
            }

            to {
                opacity: 1;
                transform: translateX(0) translateY(-50%);
            }
        }

        .slider-controls {
            position: absolute;
            bottom: 2rem;
            right: 6%;
            z-index: 30;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }

        .slider-dot.active {
            background: #fff;
            transform: scale(1.3);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 768px) {
            .hero-slider {
                height: 700px;
                border-radius: 0;
            }

            .slider-controls {
                right: 50%;
                transform: translateX(50%);
                bottom: 1.5rem;
            }
        }

        .hero-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
            transform: scale(1.05);
            transition: transform 10s ease;
        }

        .premium-hero:hover .hero-bg-image {
            transform: scale(1);
        }

        .hero-content-overlay {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 6%;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 40%, transparent 100%);
        }

        .modern-glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px) saturate(150%);
            -webkit-backdrop-filter: blur(30px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            padding: 4.5rem;
            max-width: 720px;
            box-shadow: 0 50px 100px -30px rgba(0, 0, 0, 0.6);
            animation: slideInUp 1.2s cubic-bezier(0.2, 1, 0.2, 1) forwards;
            position: relative;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .premium-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.6rem 1.25rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .premium-tag::before {
            content: '';
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--primary);
        }

        .hero-main-title {
            font-size: 5.5rem;
            font-weight: 900;
            color: #fff;
            line-height: 0.9;
            margin-bottom: 2rem;
            letter-spacing: -4px;
            font-family: 'Poppins', sans-serif;
        }

        .hero-main-title span {
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 1.35rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 3.5rem;
            line-height: 1.6;
            max-width: 90%;
            font-weight: 400;
        }

        .premium-btn-group {
            display: flex;
            gap: 1.5rem;
        }

        .p-btn {
            padding: 1.4rem 3.5rem;
            border-radius: 20px;
            font-size: 1.15rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            display: inline-block;
        }

        .p-btn-fill {
            background: #fff;
            color: #000;
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.1);
        }

        .p-btn-fill:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(255, 255, 255, 0.2);
            background: #f0f0f0;
        }

        .p-btn-outline {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .p-btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-8px);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .hero-product-showcase {
            position: absolute;
            right: 8%;
            top: 50%;
            transform: translateY(-50%);
            width: 550px;
            height: 550px;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .showcase-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(177, 178, 255, 0.3) 0%, transparent 70%);
            filter: blur(40px);
            z-index: -1;
        }

        .product-image-premium {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 50px 100px rgba(0, 0, 0, 0.5));
            animation: floatProduct 8s ease-in-out infinite;
        }

        @keyframes floatProduct {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-25px) rotate(3deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        @media (max-width: 1400px) {
            .hero-product-showcase {
                width: 450px;
                height: 450px;
                right: 5%;
            }

            .hero-main-title {
                font-size: 4.5rem;
            }
        }

        @media (max-width: 1100px) {
            .hero-product-showcase {
                display: none;
            }

            .modern-glass-card {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .hero-section-wrapper {
                width: 100%;
                margin: 0;
            }

            .premium-hero {
                height: 700px;
                border-radius: 0;
            }

            .modern-glass-card {
                padding: 2.5rem;
                margin: 0 1rem;
                background: rgba(0, 0, 0, 0.6);
            }

            .hero-main-title {
                font-size: 3.5rem;
                letter-spacing: -2px;
            }

            .premium-btn-group {
                flex-direction: column;
            }

            .p-btn {
                text-align: center;
            }
        }
    </style>



    <div class="hero-section-wrapper">
        <div class="hero-slider">
            <!-- Slide 1: Audio -->
            <div class="hero-slide active">
                <div class="premium-hero">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=2070"
                        class="hero-bg-image" alt="Background">
                    <div class="hero-content-overlay">
                        <div class="modern-glass-card">
                            <span class="premium-tag">Next-Gen Audio Experience</span>
                            <h2 class="hero-main-title">Modern<br><span>Collective</span></h2>
                            <p class="hero-description">Immerse yourself in pure sound with our flagship collection.
                                Engineered for the urban lifestyle with minimalist precision.</p>
                            <div class="premium-btn-group">
                                <a href="/?search=Headphones" class="p-btn p-btn-fill">Shop Men</a>
                                <a href="/?search=Women" class="p-btn p-btn-outline">Shop Women</a>
                            </div>
                        </div>
                    </div>
                    <div class="hero-product-showcase">
                        <div class="showcase-glow"></div>
                        <img src="https://m.media-amazon.com/images/I/71Yv8S0fVBL._AC_SL1500_.jpg"
                            class="product-image-premium" alt="Premium Headphones">
                    </div>
                </div>
            </div>

            <!-- Slide 2: Fashion -->
            <div class="hero-slide">
                <div class="premium-hero" style="background: #0f172a;">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070"
                        class="hero-bg-image" alt="Background">
                    <div class="hero-content-overlay"
                        style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.4) 40%, transparent 100%);">
                        <div class="modern-glass-card">
                            <span class="premium-tag" style="border-color: rgba(236, 72, 153, 0.3);"><span
                                    style="background: #ec4899; box-shadow: 0 0 10px #ec4899; width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 8px;"></span>New
                                Summer Arrival</span>
                            <h2 class="hero-main-title">Urban<br><span>Elegance</span></h2>
                            <p class="hero-description">Discover the perfect blend of comfort and style. Our new summer
                                collection is designed for those who dare to stand out.</p>
                            <div class="premium-btn-group">
                                <a href="/?search=Dresses" class="p-btn p-btn-fill">Explore Now</a>
                                <a href="/?search=Summer" class="p-btn p-btn-outline">View Catalog</a>
                            </div>
                        </div>
                    </div>
                    <div class="hero-product-showcase">
                        <div class="showcase-glow"
                            style="background: radial-gradient(circle, rgba(236, 72, 153, 0.3) 0%, transparent 70%);"></div>
                        <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1000"
                            class="product-image-premium" alt="Fashion Model"
                            style="object-fit: cover; border-radius: 20px; height: 80%;">
                    </div>
                </div>
            </div>

            <!-- Slide 3: Tech -->
            <div class="hero-slide">
                <div class="premium-hero" style="background: #050505;">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=2070"
                        class="hero-bg-image" alt="Background">
                    <div class="hero-content-overlay"
                        style="background: linear-gradient(90deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 40%, transparent 100%);">
                        <div class="modern-glass-card">
                            <span class="premium-tag" style="border-color: rgba(16, 185, 129, 0.3);"><span
                                    style="background: #10b981; box-shadow: 0 0 10px #10b981; width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 8px;"></span>Smart
                                Technology</span>
                            <h2 class="hero-main-title">Future<br><span>Lifestyle</span></h2>
                            <p class="hero-description">Elevate your daily routine with our curated selection of smart
                                gadgets. Innovation meets sophistication in every detail.</p>
                            <div class="premium-btn-group">
                                <a href="/?search=Gadgets" class="p-btn p-btn-fill">Shop Tech</a>
                                <a href="/?search=New" class="p-btn p-btn-outline">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="hero-product-showcase">
                        <div class="showcase-glow"
                            style="background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);"></div>
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=1000"
                            class="product-image-premium" alt="Smart Watch">
                    </div>
                </div>
            </div>

            <div class="slider-controls">
                <button class="slider-dot active" onclick="goToSlide(0)"></button>
                <button class="slider-dot" onclick="goToSlide(1)"></button>
                <button class="slider-dot" onclick="goToSlide(2)"></button>
            </div>
        </div>
    </div>
    <!-- Asymmetrical Category Grid -->
    <section class="modern-section">
        <div class="section-header">
            <h3>Featured Categories</h3>
            <a href="/?search=" class="view-all">View All</a>
        </div>
        <div class="asymmetrical-grid">
            <div class="grid-item big-item neon-glow"
                style="background-image: url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&fit=crop')">
                <div class="item-overlay">
                    <span>New Season</span>
                    <h4>Women's Fashion</h4>
                    <a href="/?search=Women" class="btn btn-sm">Explore</a>
                </div>
            </div>
            <div class="grid-item"
                style="background-image: url('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&fit=crop')">
                <div class="item-overlay">
                    <span>Premium Sound</span>
                    <h4>Audio Tech</h4>
                    <a href="/?search=Headphones" class="btn btn-sm">Shop</a>
                </div>
            </div>
            <div class="grid-item"
                style="background-image: url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&fit=crop')">
                <div class="item-overlay">
                    <span>Smart Home</span>
                    <h4>Gadgets</h4>
                    <a href="/?search=Gadgets" class="btn btn-sm">Buy</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Horizontal Scroll Section -->
    <section class="modern-section" style="position: relative;">
        <div class="section-header">
            <h3>Trending Now</h3>
        </div>

        <div class="scroll-wrapper" style="position: relative;">
            <button class="scroll-btn prev" onclick="scrollSection('trending-scroll', -400)">❮</button>
            <div class="horizontal-scroll" id="trending-scroll">
                @foreach($products->take(10) as $product)
                    <div class="scroll-item card">
                        <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="scroll-img">
                                <div class="badge-discount">-15%</div>
                                <img src="{{ !empty($product->images) && is_array($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://picsum.photos/seed/' . $product->id . '/400/400' }}"
                                    alt="{{ $product->name }}">
                            </div>
                            <div class="scroll-info">
                                <div class="rating-stars">
                                    @php $avg = $product->average_rating; @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg viewBox="0 0 20 20" style="color: {{ $i <= $avg ? '#FBBF24' : '#D1D5DB' }};">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <h5>{{ $product->name }}</h5>
                                <div class="price" style="color: var(--primary); font-weight: 800;">
                                    {{ \App\Helpers\CurrencyHelper::format($product->price) }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="scroll-btn next" onclick="scrollSection('trending-scroll', 400)">❯</button>
        </div>
    </section>

    <style>
        .scroll-wrapper {
            position: relative;
            margin: 0 -1rem;
            /* Allow buttons to float slightly outside */
            padding: 0 1rem;
        }

        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 20;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .scroll-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }

        .scroll-btn.prev {
            left: -10px;
        }

        .scroll-btn.next {
            right: -10px;
        }

        /* Hide buttons on touch devices if preferred, but keep for desktop */
        @media (max-width: 768px) {
            .scroll-btn {
                display: none;
            }
        }
    </style>

    <!-- Product Grid with Mixed Banners -->
    <section class="modern-section">
        <div class="section-header">
            <h3>Explore Products</h3>
        </div>
        <div class="mixed-product-grid">
            @foreach($products as $index => $product)
                @if($index == 4)
                    <div class="promo-banner neon-glow"
                        style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); border: 1px solid rgba(255,255,255,0.2);">
                        <div class="promo-content">
                            <span>Special Offer</span>
                            <h3 style="font-family: 'Poppins', sans-serif;">Tech Week Sale</h3>
                            <p style="color: rgba(255,255,255,0.8);">Up to 40% off on all accessories. Limited time only!</p>
                            <a href="/?search=Accessories" class="btn btn-white">View Deals</a>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <a href="{{ route('product.show', $product->id) }}"
                        style="text-decoration: none; color: inherit; display: block;">
                        <div class="card-img-wrapper">
                            <div class="badge-discount">-25%</div>
                            <img src="{{ !empty($product->images) && is_array($product->images) && count($product->images) > 0 ? $product->images[0] : 'https://picsum.photos/seed/' . $product->id . '/600/400' }}"
                                alt="{{ $product->name }}">
                            <div class="card-actions-overlay">
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="quick-add-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                            <line x1="3" y1="6" x2="21" y2="6"></line>
                                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-category">{{ $product->category->name ?? 'Fashion' }}</div>
                            <div class="card-title">{{ $product->name }}</div>
                            <div class="rating-stars">
                                @php $avg = $product->average_rating; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg viewBox="0 0 20 20" style="color: {{ $i <= $avg ? '#FBBF24' : '#D1D5DB' }};">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="rating-count">({{ $product->review_count }})</span>
                            </div>
                            <div class="card-price">{{ \App\Helpers\CurrencyHelper::format($product->price) }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .modern-section {
            margin-bottom: 5rem;
            padding: 0 2.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h3 {
            font-size: 1.75rem;
            font-weight: 800;
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Asymmetrical Grid */
        .asymmetrical-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 250px);
            gap: 1.5rem;
        }

        .grid-item {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s ease;
        }

        .big-item {
            grid-column: span 2;
            grid-row: span 2;
        }

        .grid-item:hover {
            transform: scale(1.02);
        }

        .item-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: white;
        }

        .item-overlay span {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .item-overlay h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Horizontal Scroll */
        .horizontal-scroll {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            padding: 1rem 0;
            scroll-behavior: smooth;
            scrollbar-width: none;
            /* Hide scrollbar for Firefox */
        }

        .horizontal-scroll::-webkit-scrollbar {
            display: none;
            /* Hide scrollbar for Chrome/Safari */
        }

        .scroll-item {
            min-width: 250px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 300ms ease;
            position: relative;
        }

        .scroll-info h5 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .scroll-info .price {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .scroll-item:hover {
            transform: translateY(-4px);
            background-color: var(--card-hover-bg) !important;
            border-color: var(--card-hover-border) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] .scroll-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .scroll-item:hover h5,
        .scroll-item:hover .price {
            color: var(--card-hover-text) !important;
        }

        .scroll-img {
            height: 250px;
            overflow: hidden;
        }

        .scroll-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scroll-info {
            padding: 1rem;
        }

        /* Mixed Grid */
        .mixed-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .promo-banner {
            grid-column: span 2;
            border-radius: 20px;
            padding: 3rem;
            color: white;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .promo-content span {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .promo-content h3 {
            font-size: 2.5rem;
            margin: 0.5rem 0 1rem;
        }

        .btn-white {
            background: white;
            color: black;
        }

        /* Card Improvements */
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 300ms ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-4px);
            background-color: var(--card-hover-bg) !important;
            border-color: var(--card-hover-border) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .card:hover .card-title,
        .card:hover .card-price {
            color: var(--card-hover-text) !important;
        }

        .card-img-wrapper {
            position: relative;
            height: 320px;
            overflow: hidden;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-wrapper img {
            transform: scale(1.1);
        }

        .card-actions-overlay {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .card:hover .card-actions-overlay {
            opacity: 1;
            transform: translateY(0);
        }

        .quick-add-btn {
            background: white;
            color: black;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 1rem;
        }

        .card-category {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .card-price {
            color: var(--text-primary);
            font-weight: 800;
            font-size: 1.5rem;
            margin-top: 1rem;
        }

        @media (max-width: 992px) {
            .asymmetrical-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: auto;
            }

            .big-item {
                grid-column: span 2;
                grid-row: span 1;
                height: 350px;
            }

            .grid-item {
                height: 250px;
            }

            .promo-banner {
                grid-column: span 1;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function scrollSection(id, amount) {
            document.getElementById(id).scrollBy({ left: amount, behavior: 'smooth' });
        }

        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        let slideInterval;

        function showSlide(n) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));

            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function goToSlide(n) {
            showSlide(n);
            resetInterval();
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Initialize slider
        if (slides.length > 0) {
            resetInterval();
        }
    </script>
@endsection