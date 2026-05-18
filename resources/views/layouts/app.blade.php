<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexMart</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        .top-bar {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .dropdown-select {
            position: relative;
            cursor: pointer;
            padding: 0 5px;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .dropdown-select:hover .dropdown-content {
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--bg-surface);
            min-width: 160px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 1000;
            border: 1px solid var(--border);
            border-radius: 1rem;
            /* rounded-2xl */
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-content a {
            color: var(--text-primary);
            padding: 8px 12px;
            text-decoration: none;
            display: block;
            font-size: 0.75rem;
        }

        .dropdown-content a:hover {
            background-color: var(--bg-hover);
        }

        .trending-tag {
            padding: 6px 14px;
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: 100px;
            font-size: 0.75rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .trending-tag:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--bg-hover);
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 4px 15px rgba(177, 178, 255, 0.4);
        }

        .logo-text span {
            color: var(--primary);
        }

        .suggestion-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .suggestion-item:hover {
            background: var(--bg-hover);
        }

        .sub-nav {
            display: none;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            z-index: 99;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .sub-nav-container {
            max-width: 1700px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
        }

        .sub-nav-title {
            font-weight: 800;
            font-size: 1rem;
            color: var(--primary);
            margin-right: 1.5rem;
            padding-right: 1.5rem;
            border-right: 2px solid var(--border);
            text-transform: capitalize;
        }

        .sub-nav-links {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .sub-nav-links::-webkit-scrollbar {
            display: none;
        }

        .sub-nav-link {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            transition: color 0.2s;
        }

        .sub-nav-link:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <header>
        <div class="nav-container">
            <a href="/" class="logo">
                <span class="logo-icon">N</span>
                <span class="logo-text">Nex<span>Mart</span></span>
            </a>

            <nav class="desktop-nav">
                <div class="nav-item">
                    <a href="/?search=Men" class="nav-link active-men" data-section="men">MEN</a>
                </div>
                <div class="nav-item">
                    <a href="/?search=Women" class="nav-link active-women" data-section="women">WOMEN</a>
                </div>
                <div class="nav-item">
                    <a href="/?search=Kids" class="nav-link active-kids" data-section="kids">KIDS</a>
                </div>
                <div class="nav-item">
                    <a href="/?search=Home" class="nav-link active-home" data-section="home">HOME</a>
                </div>
                <div class="nav-item">
                    <a href="/?search=Beauty" class="nav-link active-beauty" data-section="beauty">BEAUTY</a>
                </div>
                <div class="nav-item">
                    <a href="/?search=Genz" class="nav-link active-genz" data-section="genz">GENZ</a>
                </div>
            </nav>

            <div class="search-bar-wrapper"
                style="flex-grow: 1; max-width: 600px; position: relative; margin: 0 1.5rem;">
                <div class="search-bar-container"
                    style="display: flex; align-items: center; background: var(--bg-surface-hover); border-radius: 8px; border: 1px solid transparent; transition: all 0.3s; height: 44px;">
                    <div class="search-category-dropdown"
                        style="height: 100%; display: flex; align-items: center; position: relative; flex-shrink: 0; width: 130px; border-right: 1px solid var(--border);">
                        <select name="category" form="main-search-form"
                            style="appearance: none; -webkit-appearance: none; background: transparent; border: none; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); cursor: pointer; padding: 0 30px 0 15px; height: 100%; outline: none; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <option value="">All</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            style="position: absolute; right: 8px; pointer-events: none; color: var(--text-secondary);">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                    <form action="/" method="GET" id="main-search-form"
                        style="flex: 1; min-width: 0; height: 100%; display: flex; align-items: center;">
                        <input type="text" name="search" id="search-input" autocomplete="off"
                            placeholder="Search for products..." value="{{ request('search') }}"
                            style="width: 100%; background: transparent; border: none; padding: 0 12px; font-size: 0.85rem; color: var(--text-primary); outline: none; height: 100%;">
                    </form>
                    <div
                        style="display: flex; align-items: center; gap: 18px; padding: 0 16px; border-left: 1px solid var(--border); height: 60%; flex-shrink: 0;">
                        <svg class="search-camera-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round"
                            onclick="document.getElementById('visual-search-input').click()"
                            style="cursor: pointer; color: var(--text-secondary);">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                            </path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <input type="file" id="visual-search-input" accept="image/*" style="display: none;" onchange="handleVisualSearch(this)">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round"
                            onclick="document.getElementById('main-search-form').submit()"
                            style="cursor: pointer; color: var(--text-secondary);">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <!-- Search Suggestions Dropdown -->
                <div id="search-suggestions"
                    style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 0 0 1rem 1rem; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2); z-index: 2000; overflow: hidden; margin-top: 2px;">
                    <div id="recent-searches-section" style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        <div
                            style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem; display: flex; justify-content: space-between;">
                            Recent Searches
                            <span onclick="clearRecentSearches()"
                                style="color: var(--primary); cursor: pointer; text-transform: none;">Clear</span>
                        </div>
                        <div id="recent-searches-list" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <!-- JS populated -->
                        </div>
                    </div>
                    <div id="trending-searches-section" style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        <div
                            style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 0.5rem;">
                            Trending Searches</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <a href="/?search=Nike" class="trending-tag">Nike Shoes</a>
                            <a href="/?search=iPhone" class="trending-tag">iPhone 15</a>
                            <a href="/?search=Summer" class="trending-tag">Summer Collection</a>
                            <a href="/?search=Watches" class="trending-tag">Premium Watches</a>
                        </div>
                    </div>
                    <div id="live-results-section" style="max-height: 400px; overflow-y: auto;">
                        <!-- JS populated -->
                    </div>
                </div>
            </div>

            <div class="header-actions">
                <div class="dropdown-select action-item" style="border: none; padding: 0;">
                    <span id="current-curr"
                        style="font-size: 0.8rem; font-weight: 700; color: var(--text-secondary);">{{ session('currency', 'INR') }}</span>
                    <div class="dropdown-content" style="top: 100%; right: 0; margin-top: 0.5rem;">
                        <a href="{{ route('set.currency', 'INR') }}">INR (₹)</a>
                        <a href="{{ route('set.currency', 'USD') }}">USD ($)</a>
                        <a href="{{ route('set.currency', 'EUR') }}">EUR (€)</a>
                        <a href="{{ route('set.currency', 'GBP') }}">GBP (£)</a>
                    </div>
                </div>

                <!-- Theme Switcher -->
                <div class="theme-switch-wrapper" style="margin: 0; transform: scale(0.85);">
                    <label class="theme-switch" for="theme-checkbox">
                        <input type="checkbox" id="theme-checkbox" />
                        <div class="slider"></div>
                    </label>
                </div>

                @auth
                    <!-- Profile Dropdown -->
                    <div style="position: relative;">
                        <button type="button" class="action-item" id="profile-menu-btn" onclick="toggleProfileMenu()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Profile</span>
                        </button>
                        <div id="profile-dropdown"
                            style="display: none; position: absolute; right: 0; top: 100%; margin-top: 1rem; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 1.25rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 240px; z-index: 50; flex-direction: column; overflow: hidden; padding: 0.5rem;">
                            <div
                                style="padding: 1rem; border-bottom: 1px solid var(--border); background: rgba(128, 128, 128, 0.05);">
                                <div style="font-weight: 700; color: var(--text-primary);">Hello {{ auth()->user()->name }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px;">
                                    {{ auth()->user()->email }}</div>
                            </div>
                            <a href="{{ route('wishlist.index') }}"
                                style="padding: 0.75rem 1rem; color: var(--text-primary); text-decoration: none; display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; transition: background 0.2s;"
                                onmouseover="this.style.background='var(--bg-hover)'"
                                onmouseout="this.style.background='transparent'">
                                My Wishlist
                                @if(count(session('wishlist', [])) > 0)
                                    <span id="wishlist-count-dropdown"
                                        style="background: var(--primary); color: white; font-size: 0.65rem; font-weight: bold; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; padding: 0 4px;">
                                        {{ count(session('wishlist', [])) }}
                                    </span>
                                @endif
                            </a>
                            @if(auth()->user()->role === 'seller')
                                <a href="/dashboard/seller"
                                    style="padding: 0.75rem 1rem; color: var(--text-primary); text-decoration: none; display: block; font-size: 0.95rem; transition: background 0.2s;"
                                    onmouseover="this.style.background='var(--bg-hover)'"
                                    onmouseout="this.style.background='transparent'">Seller Portal</a>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <a href="/dashboard/admin"
                                    style="padding: 0.75rem 1rem; color: var(--text-primary); text-decoration: none; display: block; font-size: 0.95rem; transition: background 0.2s;"
                                    onmouseover="this.style.background='var(--bg-hover)'"
                                    onmouseout="this.style.background='transparent'">Admin Panel</a>
                            @endif
                            <form method="POST" action="/logout" style="margin: 0;">
                                @csrf
                                <button type="submit"
                                    style="width: 100%; text-align: left; padding: 0.75rem 1rem; background: transparent; border: none; border-top: 1px solid var(--border); color: var(--primary); cursor: pointer; font-size: 0.95rem; transition: background 0.2s;"
                                    onmouseover="this.style.background='var(--bg-hover)'"
                                    onmouseout="this.style.background='transparent'">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="action-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="/register?role=seller" class="action-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Sell</span>
                    </a>
                @endauth

                <a href="{{ route('chat.index') }}" class="action-item">
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        @php
                            $totalUnread = auth()->check() ? \App\Models\Message::whereHas('conversation', function ($q) {
                                $q->where('buyer_id', auth()->id())->orWhere('seller_id', auth()->id());
                            })->where('sender_id', '!=', auth()->id())->where('is_read', false)->count() : 0;
                        @endphp
                        @if($totalUnread > 0)
                            <span
                                style="position: absolute; top: -8px; right: -12px; background: var(--primary); color: white; font-size: 0.65rem; font-weight: bold; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; padding: 0 4px;">
                                {{ $totalUnread }}
                            </span>
                        @endif
                    </div>
                    <span>Messages</span>
                </a>

                @auth
                <a href="/orders" class="action-item">
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <span>Orders</span>
                </a>
                @else
                <a href="/login" class="action-item">
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <span>Orders</span>
                </a>
                @endauth

                <a href="/cart" class="action-item">
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span id="cart-count"
                            style="position: absolute; top: -8px; right: -12px; background: var(--primary); color: white; font-size: 0.65rem; font-weight: bold; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; padding: 0 4px;">
                            {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                        </span>
                    </div>
                    <span>Bag</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Visual Search Overlay -->
    <div id="visual-search-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
        <div style="background: var(--bg-surface); width: 100%; max-width: 500px; border-radius: 32px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.5); border: 1px solid var(--border); text-align: center; padding: 3rem 2rem;">
            <div id="vs-preview-container" style="position: relative; width: 200px; height: 200px; margin: 0 auto 2rem; border-radius: 24px; overflow: hidden; border: 2px solid var(--primary);">
                <img id="vs-preview-img" style="width: 100%; height: 100%; object-fit: cover;">
                <div id="vs-scanning-line" style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary); box-shadow: 0 0 20px var(--primary); animation: scan 2s linear infinite;"></div>
            </div>
            <h2 style="font-weight: 800; margin-bottom: 0.5rem; color: var(--text-primary);">AI Visual Search</h2>
            <p id="vs-status" style="color: var(--text-secondary);">Analyzing your image for matches...</p>
            
            <div id="vs-tags" style="display: flex; gap: 8px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                <!-- JS populated tags -->
            </div>
        </div>
    </div>

    <style>
        @keyframes scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
    </style>

    <div class="sub-nav" id="category-sub-nav">
        <div class="sub-nav-container">
            <div class="sub-nav-title" id="sub-nav-title">Section</div>
            <div class="sub-nav-links" id="sub-nav-content">
                <!-- Dynamic Content -->
            </div>
        </div>
    </div>

    <main>
        @if(session('success'))
            <div
                style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>ONLINE SHOPPING</h3>
                    <ul>
                        <li><a href="#">Men</a></li>
                        <li><a href="#">Women</a></li>
                        <li><a href="#">Kids</a></li>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Beauty</a></li>
                        <li><a href="#">Electronics</a></li>
                    </ul>
                    <h3 style="margin-top: 1.5rem;">USEFUL LINKS</h3>
                    <ul>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Site Map</a></li>
                        <li><a href="#">Corporate Information</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>CUSTOMER POLICIES</h3>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">T&C</a></li>
                        <li><a href="#">Terms Of Use</a></li>
                        <li><a href="#">Track Orders</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Cancellation</a></li>
                        <li><a href="#">Privacy policy</a></li>
                        <li><a href="#">Grievance Redressal</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>EXPERIENCE NEXMART APP ON MOBILE</h3>
                    <div class="app-links">
                        <a href="#"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Google Play" height="40"></a>
                        <a href="#"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                                alt="App Store" height="40"></a>
                    </div>

                    <h3 style="margin-top: 1.5rem;">KEEP IN TOUCH</h3>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="YouTube">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="footer-col guarantees">
                    <div class="guarantee-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            style="flex-shrink:0">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <p><strong>100% ORIGINAL</strong> guarantee for all products at nexmart.com</p>
                    </div>
                    <div class="guarantee-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            style="flex-shrink:0">
                            <path d="M21 2v6h-6"></path>
                            <path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
                            <path d="M3 22v-6h6"></path>
                            <path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
                        </svg>
                        <p><strong>Return within 14days</strong> of receiving your order</p>
                    </div>
                </div>
            </div>


            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} www.nexmart.com. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // Theme Toggle Logic
        const themeCheckbox = document.getElementById('theme-checkbox');

        // initialize slider position
        if (document.documentElement.getAttribute('data-theme') === 'light') {
            themeCheckbox.checked = true;
        }

        themeCheckbox.addEventListener('change', (e) => {
            const newTheme = e.target.checked ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });

        // Search Toggle Logic
        function toggleSearch() {
            const container = document.getElementById('search-input-container');
            const btn = document.getElementById('search-toggle-btn');
            const input = document.getElementById('search-input');

            container.style.display = 'flex';
            btn.style.display = 'none';
            input.focus();
        }

        // Search Suggestions Logic
        const searchInput = document.getElementById('search-input');
        const suggestionsBox = document.getElementById('search-suggestions');
        const liveResultsList = document.getElementById('live-results-section');
        const recentList = document.getElementById('recent-searches-list');
        const recentSection = document.getElementById('recent-searches-section');

        function getRecentSearches() {
            return JSON.parse(localStorage.getItem('recentSearches') || '[]');
        }

        function saveRecentSearch(term) {
            if (!term) return;
            let searches = getRecentSearches();
            searches = [term, ...searches.filter(s => s !== term)].slice(0, 5);
            localStorage.setItem('recentSearches', JSON.stringify(searches));
            renderRecentSearches();
        }

        function clearRecentSearches() {
            localStorage.removeItem('recentSearches');
            renderRecentSearches();
        }

        function renderRecentSearches() {
            const searches = getRecentSearches();
            if (searches.length === 0) {
                recentSection.style.display = 'none';
                return;
            }
            recentSection.style.display = 'block';
            recentList.innerHTML = searches.map(s => `
                <a href="/?search=${encodeURIComponent(s)}" class="trending-tag">${s}</a>
            `).join('');
        }

        let debounceTimer;
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                liveResultsList.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`);
                    const products = await response.json();

                    if (products.length > 0) {
                        liveResultsList.innerHTML = `
                            <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; padding: 1rem 1rem 0.5rem;">Products</div>
                            ${products.map(p => `
                                <a href="/product/${p.id}" class="suggestion-item">
                                    <img src="${p.images ? p.images[0] : 'https://via.placeholder.com/40'}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.9rem;">${p.name}</div>
                                        <div style="font-size: 0.8rem; color: var(--primary);">₹${p.price}</div>
                                    </div>
                                </a>
                            `).join('')}
                        `;
                    } else {
                        liveResultsList.innerHTML = '<div style="padding: 1rem; color: var(--text-secondary); font-size: 0.9rem;">No products found</div>';
                    }
                } catch (err) {
                    console.error('Search error:', err);
                }
            }, 300);
        });

        searchInput.addEventListener('focus', () => {
            suggestionsBox.style.display = 'block';
            renderRecentSearches();
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-bar-wrapper')) {
                suggestionsBox.style.display = 'none';
            }
        });

        document.getElementById('main-search-form').addEventListener('submit', (e) => {
            const query = searchInput.value.trim();
            if (query) saveRecentSearch(query);
        });

        renderRecentSearches();

        // Search Bar Focus Effect
        searchInput.addEventListener('focus', () => {
            searchInput.closest('.search-bar-container').style.borderColor = 'var(--primary)';
            searchInput.closest('.search-bar-container').style.boxShadow = '0 0 0 4px rgba(177, 178, 255, 0.15)';
            searchInput.closest('.search-bar-container').style.background = 'var(--bg-surface)';
        });
        searchInput.addEventListener('blur', () => {
            searchInput.closest('.search-bar-container').style.borderColor = 'transparent';
            searchInput.closest('.search-bar-container').style.boxShadow = 'none';
            searchInput.closest('.search-bar-container').style.background = 'var(--bg-surface-hover)';
        });

        function toggleProfileMenu() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'flex' : 'none';
        }

        // Handle Category Sub-Nav transitions
        const subNav = document.getElementById('category-sub-nav');
        const subNavContent = document.getElementById('sub-nav-content');
        const subNavTitle = document.getElementById('sub-nav-title');

        const categoryLinks = {
            men: [
                { label: 'Topwear', link: '/?search=Topwear' },
                { label: 'Bottomwear', link: '/?search=Bottomwear' },
                { label: 'Footwear', link: '/?search=Footwear' },
                { label: 'Indian Wear', link: '/?search=Indian+Festive' },
                { label: 'Innerwear', link: '/?search=Innerwear' },
                { label: 'Sports', link: '/?search=Sports' },
                { label: 'Gadgets', link: '/?search=Gadgets' },
                { label: 'Accessories', link: '/?search=Accessories' }
            ],
            women: [
                { label: 'Indian Wear', link: '/?search=Kurtas' },
                { label: 'Western Wear', link: '/?search=Dresses' },
                { label: 'Footwear', link: '/?search=Heels' },
                { label: 'Beauty', link: '/?search=Beauty' },
                { label: 'Jewellery', link: '/?search=Jewellery' },
                { label: 'Lingerie', link: '/?search=Bra' },
                { label: 'Gadgets', link: '/?search=Gadgets' }
            ],
            kids: [
                { label: 'Boys', link: '/?search=Boys' },
                { label: 'Girls', link: '/?search=Girls' },
                { label: 'Infants', link: '/?search=Infants' },
                { label: 'Toys', link: '/?search=Toys' },
                { label: 'Footwear', link: '/?search=Kids+Shoes' },
                { label: 'Accessories', link: '/?search=Kids+Accessories' }
            ],
            home: [
                { label: 'Bedding', link: '/?search=Bedsheets' },
                { label: 'Bath', link: '/?search=Towels' },
                { label: 'Lighting', link: '/?search=Lamps' },
                { label: 'Decor', link: '/?search=Decor' },
                { label: 'Kitchen', link: '/?search=Kitchen' },
                { label: 'Furniture', link: '/?search=Furniture' }
            ],
            beauty: [
                { label: 'Makeup', link: '/?search=Makeup' },
                { label: 'Skincare', link: '/?search=Skincare' },
                { label: 'Haircare', link: '/?search=Haircare' },
                { label: 'Fragrances', link: '/?search=Perfume' },
                { label: 'Appliances', link: '/?search=Straightener' },
                { label: 'Grooming', link: '/?search=Trimmers' }
            ],
            genz: [
                { label: 'Western', link: '/?search=Western' },
                { label: 'Ethnic', link: '/?search=Ethnic' },
                { label: 'Footwear', link: '/?search=Heels' },
                { label: 'Offers', link: '/?search=Under+599' }
            ]
        };

        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', (e) => {
                const section = link.getAttribute('data-section');
                if (categoryLinks[section]) {
                    e.preventDefault();

                    // Update Title
                    subNavTitle.textContent = section;

                    // Update Links
                    subNavContent.innerHTML = categoryLinks[section].map(item =>
                        `<a href="${item.link}" class="sub-nav-link">${item.label}</a>`
                    ).join('');

                    // Show Sub-Nav
                    subNav.style.display = 'flex';

                    // Redirect after a small delay to allow showing the bar if needed, 
                    // or just use it as a persistent bar if they stay on search results.
                    // For now, let's just perform the search.
                    window.location.href = link.href;
                }
            });
        });

        // Initialize sub-nav if we are on a search result page
        const urlParams = new URLSearchParams(window.location.search);
        const searchTerm = urlParams.get('search');
        if (searchTerm) {
            const matchedSection = Object.keys(categoryLinks).find(s =>
                searchTerm.toLowerCase().includes(s.toLowerCase())
            );

            if (matchedSection) {
                subNavTitle.textContent = matchedSection;
                subNavContent.innerHTML = categoryLinks[matchedSection].map(item =>
                    `<a href="${item.link}" class="sub-nav-link">${item.label}</a>`
                ).join('');
                subNav.style.display = 'flex';
            }
        }

        // Hide elements when clicking outside
        document.addEventListener('click', function (event) {
            const form = document.getElementById('search-form');
            const input = document.getElementById('search-input');
            // Don't hide search if there is text in the input
            if (form && input && !form.contains(event.target) && input.value.trim() === '') {
                const searchInputContainer = document.getElementById('search-input-container');
                const searchToggleBtn = document.getElementById('search-toggle-btn');
                if (searchInputContainer) searchInputContainer.style.display = 'none';
                if (searchToggleBtn) searchToggleBtn.style.display = 'flex';
            }

            const profileBtn = document.getElementById('profile-menu-btn');
            const profileDropdown = document.getElementById('profile-dropdown');
            if (profileBtn && profileDropdown && !profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = 'none';
            }

            // Close mega menus on click outside
            if (!event.target.closest('.nav-item')) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('is-open'));
            }
        });

        // Visual Search Logic
        function handleVisualSearch(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const overlay = document.getElementById('visual-search-overlay');
                const previewImg = document.getElementById('vs-preview-img');
                const statusText = document.getElementById('vs-status');
                const tagsContainer = document.getElementById('vs-tags');

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    overlay.style.display = 'flex';
                    
                    // Step 1: Scanning
                    setTimeout(() => {
                        statusText.innerText = "Identifying objects...";
                        tagsContainer.innerHTML = `
                            <span style="background: var(--bg-base); padding: 6px 12px; border-radius: 100px; font-size: 0.75rem; border: 1px solid var(--border);">Searching for 'Apparel'</span>
                            <span style="background: var(--bg-base); padding: 6px 12px; border-radius: 100px; font-size: 0.75rem; border: 1px solid var(--border);">Detecting patterns...</span>
                        `;
                    }, 1000);

                    // Step 2: Found matches
                    setTimeout(() => {
                        statusText.innerText = "Matches found! Redirecting...";
                        tagsContainer.innerHTML += `
                            <span style="background: var(--primary); color: white; padding: 6px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: bold;">Similar Items Found</span>
                        `;
                    }, 2500);

                    // Step 3: Redirect to search
                    setTimeout(() => {
                        window.location.href = "/?search=Summer+Collection";
                    }, 4000);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @yield('scripts')
</body>

</html>