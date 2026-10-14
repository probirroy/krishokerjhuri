<article class="product-card product-card-glass">
    <a class="product-image-link" href="{{ route('products.show', $product->slug) }}" aria-label="View {{ $product->name }}">
        <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=85' }}" alt="{{ $product->name }}" loading="lazy">
        <span class="badge">{{ $product->badge ?: 'Organic' }}</span>
    </a>
    <div class="product-body">
        <span class="product-category">{{ $product->category?->name ?? 'Fresh produce' }}</span>
        <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
        @if($product->short_description)
            <p>{{ $product->short_description }}</p>
        @endif
        <div class="price-row product-card-price">
            <strong>৳{{ number_format($product->price, 2) }}</strong>
            <span class="product-unit">/ {{ $product->unit }}</span>
            @if($product->compare_price)
                <del>৳{{ number_format($product->compare_price, 2) }}</del>
            @endif
        </div>
        @if($product->stock > 0)
            <form action="{{ route('products.add_to_cart', $product) }}" method="POST">
                @csrf
                <button class="primary-button small" type="submit">Add to cart <span aria-hidden="true">→</span></button>
            </form>
        @else
            <button class="primary-button small product-sold-out" type="button" disabled>Currently unavailable</button>
        @endif
    </div>
</article>
