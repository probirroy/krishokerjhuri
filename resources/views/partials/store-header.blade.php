<div class="store-announcement">
    <div class="container announcement-inner">
        <span class="announcement-spark" aria-hidden="true">✳</span>
        Fresh from local farms, delivered to your door
        <a href="{{ route('shop') }}">Shop the harvest <span aria-hidden="true">→</span></a>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand-wrap">
            @if(!empty($siteSettings['logo_path']))
                <img class="brand-logo" src="{{ $siteSettings['logo_path'] }}" alt="{{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}">
                <div class="brand-text">
                    <span class="brand-name">{{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</span>
                    <span class="brand-tagline">{{ $siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You' }}</span>
                </div>
            @else
                <img class="reference-wordmark" src="{{ asset('images/brand-wordmark.jpg') }}" alt="{{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }} — {{ $siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You' }}">
            @endif
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="store-navigation">
            <span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span></span>
            <span class="menu-toggle-label">Menu</span>
        </button>

        <nav class="main-nav" id="store-navigation" aria-label="Main navigation">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('home') }}#farmers">About Us</a>
            <a href="{{ route('home') }}#farmers">Our Farmers</a>
            <a href="{{ route('home') }}#featured">Blog</a>
            <a href="{{ route('home') }}#footer">Contact</a>
        </nav>

        <div class="header-actions">
            <a class="header-icon-link" href="{{ route('shop') }}" aria-label="Browse products" title="Browse products">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.4"/><path d="m16 16 4.2 4.2"/></svg>
            </a>
            @auth
                @if(auth()->user()->is_admin)
                    <a class="header-icon-link" href="{{ route('admin.dashboard') }}" aria-label="Admin panel" title="Admin panel">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="header-auth-form">
                        @csrf<button type="submit" class="header-icon-link header-logout" aria-label="Sign out" title="Sign out">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5v16h5M14 8l4 4-4 4M8 12h10"/></svg>
                        </button>
                    </form>
                @else
                    <span class="header-icon-link" aria-label="Signed in as {{ auth()->user()->name }}" title="Signed in as {{ auth()->user()->name }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="header-auth-form">
                        @csrf<button type="submit" class="header-icon-link header-logout" aria-label="Sign out" title="Sign out">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5v16h5M14 8l4 4-4 4M8 12h10"/></svg>
                        </button>
                    </form>
                @endif
            @else
                <a class="header-icon-link" href="{{ route('login') }}" aria-label="Sign in" title="Sign in">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                </a>
            @endauth
            <button class="header-icon-link theme-toggle" type="button" aria-label="Switch theme" title="Switch theme">◐</button>
            <a class="header-cart" href="{{ route('cart') }}" aria-label="Shopping cart">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 9.3a1 1 0 0 0 1 .8h8.6a1 1 0 0 0 1-.8L20 7H7.2l-.5-2.2A1 1 0 0 0 5.8 4H3Zm5 14.5A1.5 1.5 0 1 1 6.5 17 1.5 1.5 0 0 1 8 18.5Zm9 0A1.5 1.5 0 1 1 15.5 17 1.5 1.5 0 0 1 17 18.5Z"/></svg>
                <strong>{{ $cartCount ?? 0 }}</strong>
            </a>
        </div>
    </div>
</header>
