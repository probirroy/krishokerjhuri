<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Order received') }} | {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container confirmation-shell">
        <div class="confirmation-card">
            <div class="confirmation-check">✓</div>
            <p class="eyebrow">{{ __('Thank you, :name', ['name' => $order->customer_name]) }}</p>
            <h1>{{ __('Your order is in!') }}</h1>
            <p>{{ __('We’ve received your order and will contact you to confirm delivery.') }}</p>
            <p class="order-number">{{ __('Order number') }} <strong>{{ $order->order_number }}</strong></p>
            <div class="order-items">
                @foreach($order->items as $item)
                    <div class="summary-row"><span>{{ __($item->product_name) }} × {{ \App\Support\LocalizedNumber::format($item->quantity) }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($item->line_total, 2) }}</strong></div>
                @endforeach
                <div class="summary-row total"><span>{{ __('Total · Cash on delivery') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($order->total, 2) }}</strong></div>
            </div>
            <a class="primary-button" href="{{ route('shop') }}">{{ __('Continue shopping') }}</a>
        </div>
    </main>
</body>
</html>
