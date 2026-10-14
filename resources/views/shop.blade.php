<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="shop-page">
    @include('partials.store-header')

    <main class="shop-main">
        <div class="container shop-top">
            <div>
                <p class="eyebrow">Organic Collection</p>
                <h1>{{ $selectedCategory ? $selectedCategory->name : 'All Products' }}</h1>
                <p class="shop-top-description">Good food, grown with care and brought straight from local farms.</p>
            </div>
            <span class="shop-result-count">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }}</span>
        </div>

        <div class="container shop-layout">
            <aside class="shop-sidebar">
                <h3>Categories</h3>
                <ul>
                    <li><a href="{{ route('shop') }}" class="{{ !$selectedCategory ? 'active' : '' }}">All products</a></li>
                    @foreach($categories as $category)
                        <li><a href="{{ route('shop', $category->slug) }}" class="{{ $selectedCategory && $selectedCategory->id === $category->id ? 'active' : '' }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </aside>

            <div class="product-grid shop-product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>

        <div class="container pagination-wrap">
            {{ $products->links() }}
        </div>
    </main>
</body>
</html>
