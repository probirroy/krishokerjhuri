<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $query !== '' ? __('Search results for :query', ['query' => $query]) : __('Shop') }} | {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="shop-page">
    @include('partials.store-header')

    <main class="shop-main">
        <div class="container shop-top">
            <div>
                <p class="eyebrow">{{ __('Organic Collection') }}</p>
                <h1>{{ $query !== '' ? __('Search results for :query', ['query' => $query]) : ($selectedCategory ? __($selectedCategory->name) : __('All Products')) }}</h1>
                <p class="shop-top-description">{{ $query !== '' ? __('Products matching your search.') : __('Good food, grown with care and brought straight from local farms.') }}</p>
            </div>
            <span class="shop-result-count">{{ \App\Support\LocalizedNumber::format($products->total()) }} {{ __('product') }}</span>
        </div>

        <div class="container shop-layout">
            <aside class="shop-sidebar">
                <h3>{{ __('Categories') }}</h3>
                <ul>
                    <li><a href="{{ route('shop') }}" class="{{ !$selectedCategory ? 'active' : '' }}">{{ __('All Products') }}</a></li>
                    @foreach($categories as $category)
                        <li><a href="{{ route('shop', $category->slug) }}" class="{{ $selectedCategory && $selectedCategory->id === $category->id ? 'active' : '' }}">{{ __($category->name) }}</a></li>
                    @endforeach
                </ul>
            </aside>

            <div class="product-grid shop-product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
                @if($products->isEmpty())
                    <p class="shop-empty-state">{{ $query !== '' ? __('No products matched your search.') : __('No products are available in this category yet.') }}</p>
                @endif
            </div>
        </div>

        <div class="container pagination-wrap">
            {{ $products->links() }}
        </div>
    </main>
</body>
</html>
