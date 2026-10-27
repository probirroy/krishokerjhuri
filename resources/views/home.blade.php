<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</title>
    <meta name="description" content="{{ __($siteSettings['hero_description'] ?? 'Fresh from farmers to your home.') }}">
    @include('partials.pwa-head')
    <link rel="preload" as="image" href="{{ $siteSettings['hero_image_path'] ?? asset('images/home-harvest.jpg') }}" fetchpriority="high">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-page">
    @include('partials.store-header')

    <main>
        <section class="reference-hero">
            <img class="reference-hero-image" src="{{ $siteSettings['hero_image_path'] ?? asset('images/home-harvest.jpg') }}" alt="" fetchpriority="high" decoding="async">
            <div class="reference-hero-shade"></div>
            <div class="container reference-hero-inner">
                <div class="reference-hero-copy">
                    <p class="reference-eyebrow">{{ __($siteSettings['hero_eyebrow'] ?? 'Organic Vegetables') }}</p>
                    <h1>{{ __($siteSettings['hero_title'] ?? ($siteSettings['brand_name'] ?? 'Krishoker Jhuri')) }}</h1>
                    <h2>{{ __($siteSettings['hero_subtitle'] ?? 'Fresh, Natural, Healthy') }}</h2>
                    <p>{{ __($siteSettings['hero_description'] ?? 'Bringing farm-fresh organic vegetables directly from local farmers to your home.') }}</p>
                    <a class="reference-shop-button" href="{{ route('shop') }}">{{ __($siteSettings['hero_button_label'] ?? 'Shop Now') }} <span aria-hidden="true">→</span></a>
                </div>
                @if(!empty($siteSettings['hero_image_path']) || app()->getLocale() === 'bn')
                    <div class="reference-hero-badge" aria-label="{{ __($siteSettings['hero_badge'] ?? 'Pure Organic Fresh') }}">
                        <span>{{ __($siteSettings['hero_badge'] ?? 'Pure Organic Fresh') }}</span>
                        <svg viewBox="0 0 40 40" aria-hidden="true"><path d="M20 34V18m0 8c-9 0-14-5-14-13 9 0 14 4 14 13Zm0-5c0-9 5-14 14-14 0 9-5 14-14 14Z"/></svg>
                    </div>
                @endif
            </div>
        </section>

        <section class="reference-category-strip" aria-label="{{ __('Shop categories') }}">
            <div class="container reference-category-list">
                @foreach($categories as $category)
                    @php
                        $categoryArtwork = match ($category->icon) {
                            'leaf' => '<path d="M12 22V12m0 7c-6 0-9-3-9-9 6 0 9 3 9 9Zm0-5c0-6 3-9 9-9 0 6-3 9-9 9Z"/>',
                            'vegetable' => '<path d="M12 7c-1-3 1-5 4-5m-4 5c1-3 4-4 7-3-1 3-3 5-7 5-5 0-8 4-8 8a8 8 0 0 0 16 0c0-4-3-7-8-7"/>',
                            'fruit' => '<path d="M12 8c-6-4-10 1-9 7 1 5 4 8 8 7 1 0 2-1 3-1s2 1 3 1c4 1 7-3 8-8 1-6-4-10-10-6m-3 0c0-3 2-5 5-6"/>',
                            'root' => '<path d="m12 5 9 4-5 13H9L4 9l8-4Zm-3 5 5 3m-7-1 6 4m5-5-3 4m-1 5v3"/>',
                            'herb' => '<path d="M12 22V11m0 6c-6 0-9-3-9-9 6 0 9 3 9 9Zm0-4c0-6 3-9 9-9 0 6-3 9-9 9Zm-1-8c0-3-2-5-5-5 0 3 2 5 5 5Z"/>',
                            'special' => '<path d="m4 14 2 9h12l2-9m-18 0h22L18 5H6l-4 9Zm8-5v10m4-10v10"/>',
                            default => '<path d="M12 22V12m0 7c-6 0-9-3-9-9 6 0 9 3 9 9Zm0-5c0-6 3-9 9-9 0 6-3 9-9 9Z"/>',
                        };
                    @endphp
                    <a href="{{ route('shop', $category->slug) }}" class="reference-category">
                        <span class="reference-category-icon"><svg viewBox="0 0 28 28" aria-hidden="true">{!! $categoryArtwork !!}</svg></span>
                        <span>{{ __($category->name) }}</span>
                        <span class="reference-category-arrow" aria-hidden="true">→</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="reference-benefits" aria-label="{{ __('Why shop with us') }}">
            <div class="container reference-benefits-list">
                @foreach($benefits as $benefit)
                    <div class="reference-benefit">
                        <span class="reference-benefit-icon" aria-hidden="true">
                            @switch($benefit->icon)
                                @case('farmer')
                                    <svg viewBox="0 0 36 36"><path d="M8 19h20l-2 5H10l-2-5Zm3 5v5m14-5v5M12 15h12m-9 0v-3h6v3m-5-8 2-3 2 3m-2 0v5"/></svg>
                                    @break
                                @case('truck')
                                    <svg viewBox="0 0 36 36"><path d="M4 9h18v16H4zM22 15h6l5 5v5H22z"/><circle cx="11" cy="27" r="3"/><circle cx="27" cy="27" r="3"/></svg>
                                    @break
                                @case('shield')
                                    <svg viewBox="0 0 36 36"><path d="m18 4 12 5v8c0 8-5 13-12 16C11 30 6 25 6 17V9l12-5Zm-6 14 4 4 8-9"/></svg>
                                    @break
                                @default
                                    <svg viewBox="0 0 36 36"><path d="M18 30V17m0 9C10 26 6 22 6 14c8 0 12 4 12 12Zm0-7c0-8 4-12 12-12 0 8-4 12-12 12Z"/></svg>
                            @endswitch
                        </span>
                        <div>
                            <h3>{{ __($benefit->title) }}</h3>
                            <p>{{ __($benefit->subtitle) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="reference-featured" id="featured">
            <div class="container reference-featured-grid">
                <article class="reference-farmer-card" id="farmers">
                    <div class="reference-farmer-image">
                        <img src="{{ $farmerFeature->image_url ?? 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=900&q=85' }}" alt="{{ __('Fresh green crops growing at a local farm') }}" loading="lazy">
                    </div>
                    <div class="reference-farmer-copy">
                        <p class="reference-eyebrow">{{ __($farmerFeature->subtitle ?? 'Support Local Farmers') }}</p>
                        <h2>{{ __($farmerFeature->title ?? 'Support Local Farmers') }}</h2>
                        <p>{{ __($farmerFeature->description ?? 'Your choice helps local farmers grow and build a healthier, greener future.') }}</p>
                        <a href="{{ $farmerFeature->cta_url ?? route('shop') }}">{{ __($farmerFeature->cta_label ?? 'Learn More') }} <span aria-hidden="true">→</span></a>
                    </div>
                </article>

                <div class="reference-popular">
                    <div class="reference-popular-heading">
                        <div><p class="reference-eyebrow">{{ __('Explore our fresh and organic products') }}</p><h2>{{ __('Popular Categories') }}</h2></div>
                        <a href="{{ route('shop') }}" aria-label="{{ __('View all products') }}">{{ __('View all') }} <span aria-hidden="true">→</span></a>
                    </div>
                    <div class="reference-product-list">
                        @foreach($featuredProducts as $product)
                            <article class="reference-product-card">
                                <a class="reference-product-image" href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=500&q=80' }}" alt="{{ __($product->name) }}" loading="lazy">
                                </a>
                                <div class="reference-product-info">
                                    <a class="reference-product-name" href="{{ route('products.show', $product->slug) }}">{{ __($product->name) }}</a>
                                    <span>৳{{ \App\Support\LocalizedNumber::format($product->price) }} / {{ __($product->unit) }}</span>
                                    @include('partials.product-identifiers', ['product' => $product])
                                    <div class="reference-product-actions">
                                        <form action="{{ route('products.add_to_cart', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" aria-label="{{ __('Add :name to cart', ['name' => __($product->name)]) }}">+</button>
                                        </form>
                                        <form action="{{ route('products.quick_buy', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" aria-label="{{ __('Quick buy :name', ['name' => __($product->name)]) }}">{{ __('Buy') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.store-footer')
</body>
</html>
