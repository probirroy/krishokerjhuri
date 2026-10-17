<article class="product-card product-card-glass">
    <a class="product-image-link" href="{{ route('products.show', $product->slug) }}" aria-label="{{ __('View :name', ['name' => __($product->name)]) }}">
        <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=85' }}" alt="{{ __($product->name) }}" loading="lazy" decoding="async">
        <span class="badge">{{ __($product->badge ?: 'Organic') }}</span>
    </a>
    <div class="product-body">
        <span class="product-category">{{ __($product->category?->name ?? 'Fresh produce') }}</span>
        <h3><a href="{{ route('products.show', $product->slug) }}">{{ __($product->name) }}</a></h3>
        @if($product->short_description)
            <p>{{ __($product->short_description) }}</p>
        @endif
        @include('partials.product-identifiers', ['product' => $product])
        <div class="price-row product-card-price">
            <strong>৳{{ \App\Support\LocalizedNumber::format($product->price, 2) }}</strong>
            <span class="product-unit">/ {{ __($product->unit) }}</span>
            @if($product->compare_price)
                <del>৳{{ \App\Support\LocalizedNumber::format($product->compare_price, 2) }}</del>
            @endif
        </div>
        @if($product->stock > 0)
            <div class="product-actions product-card-actions">
                <form action="{{ route('products.add_to_cart', $product) }}" method="POST">
                    @csrf
                    <button class="primary-button small" type="submit">{{ __('Add to cart') }} <span aria-hidden="true">→</span></button>
                </form>
                <form action="{{ route('products.quick_buy', $product) }}" method="POST">
                    @csrf
                    <button class="secondary-button small" type="submit">{{ __('Quick buy') }}</button>
                </form>
            </div>
        @else
            <button class="primary-button small product-sold-out" type="button" disabled>{{ __('Currently unavailable') }}</button>
        @endif
    </div>
</article>
