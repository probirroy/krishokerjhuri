<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="cart-page">
    @include('partials.store-header')

    <main class="container cart-shell">
        <h1>Your cart</h1>
        @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
        @if(session('success'))<div class="form-success">{{ session('success') }}</div>@endif

        @if($items->isEmpty())
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a class="primary-button" href="{{ route('shop') }}">Continue shopping</a>
            </div>
        @else
            <div class="cart-layout">
                <div class="cart-items">
                    @foreach($items as $item)
                        <div class="cart-item">
                            <img src="{{ $item->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->name }}">
                            <div class="cart-item-body">
                                <h3><a href="{{ route('products.show', $item->slug) }}">{{ $item->name }}</a></h3>
                                <p>৳{{ number_format($item->price, 2) }} each · {{ $item->stock }} available</p>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-form">
                                    @csrf
                                    <label>
                                        Quantity
                                        <input type="number" name="quantity" min="0" max="{{ $item->stock }}" value="{{ $item->quantity }}">
                                    </label>
                                    <button type="submit" class="secondary-button small">Update</button>
                                </form>
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-button">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <aside class="cart-summary">
                    <h3>Order summary</h3>
                    <div class="summary-row"><span>Subtotal</span><strong>৳{{ number_format($subtotal, 2) }}</strong></div>
                    <div class="summary-row"><span>Shipping</span><strong>৳{{ number_format($shipping, 2) }}</strong></div>
                    <div class="summary-row total"><span>Total</span><strong>৳{{ number_format($total, 2) }}</strong></div>
                    <a class="primary-button" href="{{ route('checkout') }}">Proceed to checkout</a>
                </aside>
            </div>
        @endif
    </main>
</body>
</html>
