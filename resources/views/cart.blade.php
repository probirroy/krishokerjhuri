<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Your cart') }} | {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</title>
    @include('partials.pwa-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cart-page">
    @include('partials.store-header')

    <main class="container cart-shell">
        <h1>{{ __('Your cart') }}</h1>
        @if($errors->any())<div class="form-errors">{{ __($errors->first()) }}</div>@endif
        @if(session('success'))<div class="form-success">{{ __(session('success')) }}</div>@endif

        @if($items->isEmpty())
            <div class="empty-cart">
                <p>{{ __('Your cart is empty.') }}</p>
                <a class="primary-button" href="{{ route('shop') }}">{{ __('Continue shopping') }}</a>
            </div>
        @else
            <div class="cart-layout">
                <div class="cart-items">
                    @foreach($items as $item)
                        <div class="cart-item">
                            <img src="{{ $item->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80' }}" alt="{{ __($item->name) }}">
                            <div class="cart-item-body">
                                <h3><a href="{{ route('products.show', $item->slug) }}">{{ __($item->name) }}</a></h3>
                                <p>৳{{ \App\Support\LocalizedNumber::format($item->price, 2) }} {{ __('each · :count available', ['count' => \App\Support\LocalizedNumber::format($item->stock)]) }}</p>
                                @include('partials.product-identifiers', ['product' => $item])
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-form">
                                    @csrf
                                    <label>
                                        {{ __('Quantity') }}
                                        <input type="number" name="quantity" min="0" max="{{ $item->stock }}" value="{{ $item->quantity }}" aria-label="{{ __('Quantity') }} {{ __($item->name) }}">
                                    </label>
                                    <button type="submit" class="secondary-button small">{{ __('Update') }}</button>
                                </form>
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-button">{{ __('Remove') }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <aside class="cart-summary">
                    <h3>{{ __('Order summary') }}</h3>
                    <div class="summary-row"><span>{{ __('Subtotal') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($subtotal, 2) }}</strong></div>
                    <div class="summary-row"><span>{{ __('Delivery charge') }}</span><strong>{{ __('Calculated at checkout') }}</strong></div>
                    <a class="primary-button" href="{{ route('checkout') }}">{{ __('Proceed to checkout') }}</a>
                </aside>
            </div>
        @endif
    </main>
</body>
</html>
