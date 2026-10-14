<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container product-detail">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('shop', $product->category?->slug) }}">{{ $product->category?->name ?? 'Shop' }}</a><span>/</span>
            <span>{{ $product->name }}</span>
        </nav>
        <div class="detail-grid">
            <div class="detail-image">
                <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80' }}" alt="{{ $product->name }}">
            </div>
            <section class="detail-copy">
                <p class="eyebrow">{{ $product->category?->name ?? 'Fresh produce' }}</p>
                <h1>{{ $product->name }}</h1>
                <div class="price-row detail-price">
                    <strong>৳{{ number_format($product->price, 2) }} <small>/ {{ $product->unit }}</small></strong>
                    @if($product->compare_price)<span>৳{{ number_format($product->compare_price, 2) }}</span>@endif
                </div>
                <p class="detail-description">{{ $product->description ?: $product->short_description }}</p>
                <p class="stock-status">{{ $product->stock > 0 ? 'In stock · '.$product->stock.' available' : 'Currently out of stock' }}</p>
                @if($product->stock > 0)
                    <form action="{{ route('products.add_to_cart', $product) }}" method="POST">
                        @csrf
                        <button class="primary-button" type="submit">Add to cart <span>→</span></button>
                    </form>
                @endif
            </section>
        </div>

        @if($relatedProducts->isNotEmpty())
            <section class="related-section" id="featured">
                <div class="section-head"><div><p class="eyebrow">From the same harvest</p><h2>You may also like</h2></div></div>
                <div class="product-grid">
                    @foreach($relatedProducts as $related)
                        @include('partials.product-card', ['product' => $related])
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>
