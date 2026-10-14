<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container checkout-shell">
        <p class="eyebrow">Almost there</p>
        <h1>Checkout</h1>
        @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
        <div class="checkout-layout">
            <form class="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                @csrf
                <h2>Delivery details</h2>
                <label>Full name<input name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required autocomplete="name"></label>
                <label>Email (optional)<input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" autocomplete="email"></label>
                <label>Phone number<input name="phone" value="{{ old('phone', auth()->user()?->mobile) }}" required autocomplete="tel"></label>
                <label>Street address<textarea name="address" required autocomplete="street-address">{{ old('address') }}</textarea></label>
                <label>District<input name="district" value="{{ old('district') }}" required></label>
                <h2>Payment</h2>
                <label class="radio-option"><input type="radio" name="payment_method" value="cod" checked> Cash on delivery</label>
                <button class="primary-button" type="submit">Place order · ৳{{ number_format($total, 2) }}</button>
            </form>
            <aside class="cart-summary">
                <h2>Your order</h2>
                @foreach($items as $item)
                    <div class="summary-row"><span>{{ $item->name }} × {{ $item->quantity }}</span><strong>৳{{ number_format($item->price * $item->quantity, 2) }}</strong></div>
                @endforeach
                <div class="summary-row"><span>Subtotal</span><strong>৳{{ number_format($subtotal, 2) }}</strong></div>
                <div class="summary-row"><span>Delivery</span><strong>৳{{ number_format($shipping, 2) }}</strong></div>
                <div class="summary-row total"><span>Total</span><strong>৳{{ number_format($total, 2) }}</strong></div>
            </aside>
        </div>
    </main>
</body>
</html>
