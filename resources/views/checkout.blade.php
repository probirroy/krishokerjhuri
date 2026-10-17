<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Checkout') }} | {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container checkout-shell">
        <p class="eyebrow">{{ __('Almost there') }}</p>
        <h1>{{ __('Checkout') }}</h1>
        @if($errors->any())<div class="form-errors">{{ __($errors->first()) }}</div>@endif
        <div class="checkout-layout">
            <form class="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                @csrf
                <h2>{{ __('Delivery details') }}</h2>
                <label>{{ __('Full name') }}<input name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required autocomplete="name" placeholder="{{ __('Enter your full name') }}"></label>
                <label>{{ __('Email (optional)') }}<input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" autocomplete="email" placeholder="{{ __('Enter your email address') }}"></label>
                <label>{{ __('Phone number') }}<input name="phone" value="{{ old('phone', auth()->user()?->mobile) }}" required autocomplete="tel" placeholder="{{ __('01XXXXXXXXX') }}"></label>
                <label>{{ __('Street address') }}<textarea name="address" required autocomplete="street-address" placeholder="{{ __('House, road, area') }}">{{ old('address') }}</textarea></label>
                <label>{{ __('District') }}<input name="district" value="{{ old('district') }}" required></label>
                <h2>{{ __('Payment') }}</h2>
                <label class="radio-option"><input type="radio" name="payment_method" value="cod" checked> {{ __('Cash on delivery') }}</label>
                <button class="primary-button" type="submit">{{ __('Place order · ৳:total', ['total' => \App\Support\LocalizedNumber::format($total, 2)]) }}</button>
            </form>
            <aside class="cart-summary">
                <h2>{{ __('Your order') }}</h2>
                @foreach($items as $item)
                    <div class="checkout-item">
                        <div class="summary-row"><span>{{ __($item->name) }} × {{ \App\Support\LocalizedNumber::format($item->quantity) }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($item->price * $item->quantity, 2) }}</strong></div>
                        @include('partials.product-identifiers', ['product' => $item])
                    </div>
                @endforeach
                <div class="summary-row"><span>{{ __('Subtotal') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($subtotal, 2) }}</strong></div>
                <div class="summary-row"><span>{{ __('Delivery') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($shipping, 2) }}</strong></div>
                <div class="summary-row total"><span>{{ __('Total') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($total, 2) }}</strong></div>
            </aside>
        </div>
    </main>
</body>
</html>
