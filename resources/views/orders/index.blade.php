@extends('pages.layout')

@section('title', __('Order history'))
@section('description', __('View your recent orders.'))

@section('content')
    <section class="account-orders-page">
        <div class="container account-orders-container">
            <p class="story-eyebrow">{{ __('My account') }}</p>
            <h1>{{ __('Order history') }}</h1>
            <p class="account-orders-intro">{{ __('Orders placed while signed in to your account appear here.') }}</p>

            @forelse($orders as $order)
                <article class="account-order-card">
                    <div class="account-order-header">
                        <div>
                            <span>{{ __('Order number') }}</span>
                            <h2>{{ $order->order_number }}</h2>
                        </div>
                        <span class="account-order-status">{{ __(ucfirst($order->status)) }}</span>
                    </div>
                    <p class="account-order-date">{{ $order->created_at->format('M j, Y · g:i a') }}</p>
                    <ul>
                        @foreach($order->items as $item)
                            <li>{{ __($item->product_name) }} × {{ \App\Support\LocalizedNumber::format($item->quantity) }}</li>
                        @endforeach
                    </ul>
                    <div class="account-order-total">
                        <strong>{{ __('Total') }}: ৳{{ \App\Support\LocalizedNumber::format($order->total, 2) }}</strong>
                        <a href="{{ URL::signedRoute('orders.invoice', ['orderNumber' => $order->order_number]) }}" rel="nofollow">{{ __('View invoice') }} →</a>
                    </div>
                </article>
            @empty
                <div class="account-orders-empty">
                    <h2>{{ __('No account orders yet') }}</h2>
                    <p>{{ __('Orders you place while signed in will show up here.') }}</p>
                    <a class="story-button story-button-dark" href="{{ route('shop') }}">{{ __('Explore the shop') }} <span aria-hidden="true">→</span></a>
                </div>
            @endforelse

            @if($orders->hasPages())
                <div class="journal-pagination">{{ $orders->links() }}</div>
            @endif
        </div>
    </section>
@endsection
