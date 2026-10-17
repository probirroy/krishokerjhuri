<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $productImages = $product->galleryImages();
        $productImageUrls = array_map(
            fn (string $image): string => \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                ? $image
                : asset(ltrim($image, '/')),
            $productImages,
        );
        $displayImage = $productImageUrls[0] ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80';
        $pageTitle = $product->seo_title ? __($product->seo_title) : __($product->name).' | '.__($siteSettings['brand_name'] ?? 'Krishoker Jhuri');
        $pageDescription = $product->seo_description
            ? __($product->seo_description)
            : \Illuminate\Support\Str::limit(strip_tags(__($product->short_description ?: $product->description ?: $product->name)), 160);
        $pageImage = $productImageUrls[0] ?? asset('images/home-harvest.jpg');
        $structuredProduct = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => __($product->name),
            'description' => strip_tags(__($product->description ?: $product->short_description ?: $product->name)),
            'image' => $productImageUrls ?: [$pageImage],
            'sku' => $product->sku,
            'category' => $product->category ? __($product->category->name) : null,
            'offers' => [
                '@type' => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'BDT',
                'price' => number_format($product->price, 2, '.', ''),
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ];
        $barcodeDigits = $product->barcode ? preg_split('//', $product->barcode, -1, PREG_SPLIT_NO_EMPTY) : [];
        if (count($barcodeDigits) >= 8 && in_array(count($barcodeDigits), [8, 12, 13, 14], true)) {
            $checkDigit = (int) array_pop($barcodeDigits);
            $weightedTotal = 0;
            foreach (array_reverse($barcodeDigits) as $index => $digit) {
                $weightedTotal += (int) $digit * ($index % 2 === 0 ? 3 : 1);
            }
            $isValidGtin = preg_match('/^\d+$/', $product->barcode)
                && (10 - ($weightedTotal % 10)) % 10 === $checkDigit;
        } else {
            $isValidGtin = false;
        }
        if ($isValidGtin) {
            $structuredProduct['gtin'] = $product->barcode;
        }
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    @if($product->seo_keywords)<meta name="keywords" content="{{ __($product->seo_keywords) }}">@endif
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="product:price:amount" content="{{ number_format($product->price, 2, '.', '') }}">
    <meta property="product:price:currency" content="BDT">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <script type="application/ld+json">
        @json($structuredProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container product-detail">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ __('Home') }}</a><span>/</span>
            <a href="{{ route('shop', $product->category?->slug) }}">{{ __($product->category?->name ?? 'Shop') }}</a><span>/</span>
            <span>{{ __($product->name) }}</span>
        </nav>
        <div class="detail-grid">
            <div class="detail-gallery" data-product-gallery>
                <div class="detail-image">
                    <img src="{{ $displayImage }}" alt="{{ __($product->name) }}" data-gallery-main fetchpriority="high" decoding="async">
                </div>
                @if(count($productImageUrls) > 1)
                    <div class="detail-thumbnails" aria-label="{{ __('Product images') }}">
                        @foreach($productImageUrls as $index => $image)
                            <button class="detail-thumbnail" type="button" data-gallery-image="{{ $image }}" aria-label="{{ __('Show product image :number', ['number' => $index + 1]) }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                                <img src="{{ $image }}" alt="" loading="lazy" decoding="async">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <section class="detail-copy">
                <p class="eyebrow">{{ __($product->category?->name ?? 'Fresh produce') }}</p>
                <h1>{{ __($product->name) }}</h1>
                <div class="price-row detail-price">
                    <strong>৳{{ \App\Support\LocalizedNumber::format($product->price, 2) }} <small>/ {{ __($product->unit) }}</small></strong>
                    @if($product->compare_price)<span>৳{{ \App\Support\LocalizedNumber::format($product->compare_price, 2) }}</span>@endif
                </div>
                @include('partials.product-identifiers', ['product' => $product])
                <p class="detail-description product-description">{{ __($product->description ?: $product->short_description) }}</p>
                <p class="stock-status">{{ $product->stock > 0 ? __('In stock · :count available', ['count' => \App\Support\LocalizedNumber::format($product->stock)]) : __('Currently out of stock') }}</p>
                @if($product->stock > 0)
                    <div class="product-actions detail-product-actions">
                        <form action="{{ route('products.add_to_cart', $product) }}" method="POST">
                            @csrf
                            <button class="primary-button" type="submit">{{ __('Add to cart') }} <span>→</span></button>
                        </form>
                        <form action="{{ route('products.quick_buy', $product) }}" method="POST">
                            @csrf
                            <button class="secondary-button" type="submit">{{ __('Quick buy') }} <span aria-hidden="true">⚡</span></button>
                        </form>
                    </div>
                @endif
            </section>
        </div>

        @if($relatedProducts->isNotEmpty())
            <section class="related-section" id="featured">
                <div class="section-head"><div><p class="eyebrow">{{ __('From the same harvest') }}</p><h2>{{ __('You may also like') }}</h2></div></div>
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
