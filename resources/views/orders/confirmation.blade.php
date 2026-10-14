<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order received | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.store-header')
    <main class="container confirmation-shell">
        <div class="confirmation-card">
            <div class="confirmation-check">✓</div>
            <p class="eyebrow">Thank you, {{ $order->customer_name }}</p>
            <h1>Your order is in!</h1>
            <p>We’ve received your order and will contact you to confirm delivery.</p>
            <p class="order-number">Order number <strong>{{ $order->order_number }}</strong></p>
            <div class="order-items">
                @foreach($order->items as $item)
                    <div class="summary-row"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><strong>৳{{ number_format($item->line_total, 2) }}</strong></div>
                @endforeach
                <div class="summary-row total"><span>Total · Cash on delivery</span><strong>৳{{ number_format($order->total, 2) }}</strong></div>
            </div>
            <a class="primary-button" href="{{ route('shop') }}">Continue shopping</a>
        </div>
    </main>
</body>
</html>
