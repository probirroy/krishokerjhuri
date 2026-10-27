<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ __('Invoice') }} {{ $order->order_number }} | {{ __($siteSettings['brand_name'] ?? config('app.name')) }}</title>
    <style id="invoice-styles">
        :root { color-scheme: light; font-family: Arial, "Segoe UI", sans-serif; color: #17251c; background: #eef1ed; font-synthesis: none; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; }
        .invoice-actions { display: flex; justify-content: center; gap: 12px; margin: 0 auto 20px; }
        .invoice-actions button { border: 0; border-radius: 6px; padding: 11px 18px; background: #176b3a; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .invoice-actions button:disabled { opacity: .6; cursor: wait; }
        .invoice-page { width: 186mm; min-height: 273mm; margin: 0 auto; padding: 0; background: white; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; border-bottom: 2px solid #176b3a; padding-bottom: 14px; }
        .invoice-brand h1 { margin: 0 0 5px; color: #176b3a; font-size: 22px; }
        .invoice-brand p, .invoice-meta p { margin: 3px 0; color: #526158; font-size: 11px; }
        .invoice-title { margin: 0 0 6px; text-align: right; font-size: 21px; letter-spacing: .08em; }
        .invoice-meta { text-align: right; }
        .order-barcode { display: block; width: 230px; height: 62px; margin: 6px 0 0 auto; }
        .shipping-label { margin: 16px 0; padding: 12px 14px; border: 1px dashed #53655a; border-radius: 4px; }
        .shipping-label-title { display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #d9ded9; font-size: 12px; font-weight: 700; letter-spacing: .08em; }
        .shipping-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; padding-top: 10px; }
        .shipping-parties h2 { margin: 0 0 5px; color: #526158; font-size: 10px; text-transform: uppercase; letter-spacing: .08em; }
        .shipping-parties p { margin: 3px 0; font-size: 11px; line-height: 1.4; overflow-wrap: anywhere; }
        .invoice-section-title { margin: 16px 0 8px; font-size: 13px; }
        .invoice-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .invoice-table th { padding: 8px 6px; background: #edf4ee; text-align: left; }
        .invoice-table td { padding: 8px 6px; border-bottom: 1px solid #e0e5e0; vertical-align: middle; }
        .invoice-table .number { text-align: right; white-space: nowrap; }
        .product-name { font-weight: 700; }
        .product-link { display: block; max-width: 80mm; margin-top: 3px; color: #526158; font-size: 8px; overflow-wrap: anywhere; }
        .product-qr { width: 25mm; height: 25mm; }
        .product-qr svg { display: block; width: 24mm; height: 24mm; }
        .invoice-totals { width: min(82mm, 100%); margin: 14px 0 0 auto; }
        .invoice-total-row { display: flex; justify-content: space-between; gap: 16px; padding: 5px 0; font-size: 11px; }
        .invoice-total-row.grand-total { margin-top: 3px; padding-top: 9px; border-top: 2px solid #176b3a; font-size: 15px; font-weight: 700; }
        .invoice-note { margin-top: 22px; padding-top: 10px; border-top: 1px solid #d9ded9; color: #526158; font-size: 10px; text-align: center; }
        @media print {
            @page { size: A4 portrait; margin: 12mm; }
            :root { background: white; }
            body { padding: 0; }
            .invoice-actions { display: none !important; }
            .invoice-page { width: auto; min-height: 0; margin: 0; }
            .shipping-label, .invoice-header, .invoice-totals, tr { break-inside: avoid; page-break-inside: avoid; }
            .invoice-table thead { display: table-header-group; }
            .invoice-page, .shipping-label, .invoice-table th { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <nav class="invoice-actions" aria-label="{{ __('Invoice actions') }}">
        <button type="button" data-print-invoice>{{ __('Print / Save PDF') }}</button>
        <button type="button" data-download-invoice data-ready-label="{{ __('Download invoice file') }}" disabled>{{ __('Preparing download…') }}</button>
    </nav>

    <main class="invoice-page" data-invoice-document data-invoice-number="{{ $order->order_number }}">
        <header class="invoice-header">
            <div class="invoice-brand">
                <h1>{{ __($siteSettings['brand_name'] ?? config('app.name')) }}</h1>
                @if(!empty($siteSettings['store_location']))<p>{{ $siteSettings['store_location'] }}</p>@endif
                @if(!empty($siteSettings['contact_email']))<p>{{ $siteSettings['contact_email'] }}</p>@endif
            </div>
            <div class="invoice-meta">
                <h2 class="invoice-title">{{ __('INVOICE') }}</h2>
                <p><strong>{{ __('Order number') }}:</strong> {{ $order->order_number }}</p>
                <p><strong>{{ __('Date') }}:</strong> {{ $order->created_at->format('M j, Y · g:i a') }}</p>
                <p><strong>{{ __('Status') }}:</strong> {{ ucfirst($order->status) }}</p>
                <svg class="order-barcode" data-order-barcode="{{ $order->order_number }}" role="img" aria-label="{{ __('Barcode for order') }} {{ $order->order_number }}"></svg>
            </div>
        </header>

        <section class="shipping-label" aria-label="{{ __('Shipping label') }}">
            <div class="shipping-label-title">
                <span>{{ __('SHIPPING LABEL') }}</span>
                <span>{{ __('Cash on delivery') }}</span>
            </div>
            <div class="shipping-parties">
                <div>
                    <h2>{{ __('Ship to') }}</h2>
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->address }}</p>
                    <p>{{ $order->district }}</p>
                    <p>{{ $order->phone }}</p>
                    @if($order->email)<p>{{ $order->email }}</p>@endif
                </div>
                <div>
                    <h2>{{ __('From') }}</h2>
                    <p><strong>{{ __($siteSettings['brand_name'] ?? config('app.name')) }}</strong></p>
                    @if(!empty($siteSettings['store_location']))<p>{{ $siteSettings['store_location'] }}</p>@endif
                    @if(!empty($siteSettings['contact_email']))<p>{{ $siteSettings['contact_email'] }}</p>@endif
                    <p><strong>{{ __('Order number') }}:</strong> {{ $order->order_number }}</p>
                </div>
            </div>
        </section>

        <h2 class="invoice-section-title">{{ __('Order items') }}</h2>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Product QR') }}</th>
                    <th class="number">{{ __('Qty') }}</th>
                    <th class="number">{{ __('Unit price') }}</th>
                    <th class="number">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php($productUrl = $item->product ? route('products.show', $item->product->slug) : route('shop'))
                    <tr>
                        <td>
                            <span class="product-name">{{ $item->product_name }}</span>
                            <span class="product-link">{{ $productUrl }}</span>
                        </td>
                        <td><div class="product-qr" data-product-qr data-qr-url="{{ $productUrl }}"></div></td>
                        <td class="number">{{ \App\Support\LocalizedNumber::format($item->quantity) }} {{ $item->unit }}</td>
                        <td class="number">৳{{ \App\Support\LocalizedNumber::format($item->unit_price, 2) }}</td>
                        <td class="number">৳{{ \App\Support\LocalizedNumber::format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="invoice-totals" aria-label="{{ __('Order totals') }}">
            <div class="invoice-total-row"><span>{{ __('Subtotal') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($order->subtotal, 2) }}</strong></div>
            <div class="invoice-total-row"><span>{{ __('Delivery charge') }} · {{ strtolower(trim($order->district)) === 'dhaka' ? __('Inside Dhaka') : __('Outside Dhaka') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($order->shipping, 2) }}</strong></div>
            <div class="invoice-total-row grand-total"><span>{{ __('Total due') }} · {{ __('Cash on delivery') }}</span><strong>৳{{ \App\Support\LocalizedNumber::format($order->total, 2) }}</strong></div>
        </section>

        <footer class="invoice-note">{{ __('Thank you for shopping with us.') }} · {{ __($siteSettings['brand_name'] ?? config('app.name')) }}</footer>
    </main>
</body>
</html>
