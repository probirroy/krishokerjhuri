<div class="store-announcement">
    <div class="container announcement-inner">
        <span class="announcement-spark" aria-hidden="true">✳</span>
        {{ __('Fresh from local farms, delivered to your door') }}
        <a href="{{ route('shop') }}">{{ __('Shop the harvest') }} <span aria-hidden="true">→</span></a>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand-wrap">
            @if(!empty($siteSettings['logo_path']))
                <img class="brand-logo" src="{{ $siteSettings['logo_path'] }}" alt="{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}">
                <div class="brand-text">
                    <span class="brand-name">{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</span>
                    <span class="brand-tagline">{{ __($siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}</span>
                </div>
            @elseif(app()->getLocale() === 'bn')
                <div class="brand-text">
                    <span class="brand-name">{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</span>
                    <span class="brand-tagline">{{ __($siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}</span>
                </div>
            @else
                <img class="reference-wordmark" src="{{ asset('images/brand-wordmark.jpg') }}" alt="{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }} — {{ __($siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}">
            @endif
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="store-navigation">
            <span class="menu-toggle-icon" aria-hidden="true"><span></span><span></span></span>
            <span class="menu-toggle-label">{{ __('Menu') }}</span>
        </button>

        <nav class="main-nav" id="store-navigation" aria-label="{{ __('Main navigation') }}">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
            <a class="{{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">{{ __('Shop') }}</a>
            <a href="{{ route('home') }}#farmers">{{ __('About Us') }}</a>
            <a href="{{ route('home') }}#farmers">{{ __('Our Farmers') }}</a>
            <a href="{{ route('home') }}#featured">{{ __('Blog') }}</a>
            <a href="{{ route('home') }}#footer">{{ __('Contact') }}</a>
        </nav>

        <div class="header-search{{ request()->routeIs('shop') && request()->filled('q') ? ' is-open' : '' }}">
            <button class="header-search-toggle header-icon-link" type="button" aria-expanded="{{ request()->routeIs('shop') && request()->filled('q') ? 'true' : 'false' }}" aria-label="{{ __('Search products') }}" title="{{ __('Search products') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.4"/><path d="m16 16 4.2 4.2"/></svg>
            </button>
            <form action="{{ route('shop') }}" method="GET" role="search" class="header-search-form">
                <label class="visually-hidden" for="store-product-search">{{ __('Search products') }}</label>
                <input id="store-product-search" type="search" name="q" value="{{ request('q') }}" maxlength="100" placeholder="{{ __('Search products') }}">
                <button type="submit">{{ __('Search') }}</button>
            </form>
        </div>

        <div class="header-actions">
            @auth
                @if(auth()->user()->is_admin)
                    <a class="header-icon-link" href="{{ route('admin.dashboard') }}" aria-label="{{ __('Admin panel') }}" title="{{ __('Admin panel') }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="header-auth-form">
                        @csrf<button type="submit" class="header-icon-link header-logout" aria-label="{{ __('Sign out') }}" title="{{ __('Sign out') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5v16h5M14 8l4 4-4 4M8 12h10"/></svg>
                        </button>
                    </form>
                @else
                    <span class="header-icon-link" aria-label="{{ __('Signed in as :name', ['name' => auth()->user()->name]) }}" title="{{ __('Signed in as :name', ['name' => auth()->user()->name]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="header-auth-form">
                        @csrf<button type="submit" class="header-icon-link header-logout" aria-label="{{ __('Sign out') }}" title="{{ __('Sign out') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5v16h5M14 8l4 4-4 4M8 12h10"/></svg>
                        </button>
                    </form>
                @endif
            @else
                <a class="header-icon-link" href="{{ route('login') }}" aria-label="{{ __('Sign in') }}" title="{{ __('Sign in') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><path d="M5.2 20c.3-3.5 2.9-5.7 6.8-5.7s6.5 2.2 6.8 5.7"/></svg>
                </a>
            @endauth
            @include('partials.language-switch')
            <button class="header-icon-link theme-toggle" type="button" aria-label="{{ __('Switch theme') }}" title="{{ __('Switch theme') }}">◐</button>
            <a class="header-cart" href="{{ route('cart') }}" aria-label="{{ __('Shopping cart') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 9.3a1 1 0 0 0 1 .8h8.6a1 1 0 0 0 1-.8L20 7H7.2l-.5-2.2A1 1 0 0 0 5.8 4H3Zm5 14.5A1.5 1.5 0 1 1 6.5 17 1.5 1.5 0 0 1 8 18.5Zm9 0A1.5 1.5 0 1 1 15.5 17 1.5 1.5 0 0 1 17 18.5Z"/></svg>
                <strong>{{ \App\Support\LocalizedNumber::format($cartCount ?? 0) }}</strong>
            </a>
        </div>
    </div>
</header>
