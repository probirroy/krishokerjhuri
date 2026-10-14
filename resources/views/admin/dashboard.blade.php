@extends('admin.layout')

@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
    <div class="admin-page-head">
        <div><h1>Good day, {{ auth()->user()->name }}</h1><p>Here’s what’s happening in your store.</p></div>
        <a href="{{ route('admin.products.create') }}" class="primary-button">＋ Add product</a>
    </div>
    <div class="admin-stats">
        <article><span>Products</span><strong>{{ $productCount }}</strong><a href="{{ route('admin.products.index') }}">Manage products →</a></article>
        <article><span>Categories</span><strong>{{ $categoryCount }}</strong><a href="{{ route('admin.categories.index') }}">Manage categories →</a></article>
        <article><span>Total orders</span><strong>{{ $orderCount }}</strong><a href="{{ route('admin.orders') }}">View orders →</a></article>
        <article><span>Needs attention</span><strong>{{ $pendingOrderCount }}</strong><a href="{{ route('admin.orders') }}">Pending orders →</a></article>
    </div>
    <section class="admin-panel">
        <div class="panel-head"><div><h2>Recent orders</h2><p>Latest customer checkouts</p></div><a class="text-link" href="{{ route('admin.orders') }}">All orders →</a></div>
        @if($recentOrders->isEmpty())
            <div class="admin-empty">No orders yet. Orders placed by customers will appear here.</div>
        @else
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>Order</th><th>Customer</th><th>Placed</th><th>Total</th><th>Status</th></tr></thead><tbody>
                @foreach($recentOrders as $order)
                    <tr><td><strong>{{ $order->order_number }}</strong></td><td>{{ $order->customer_name }}</td><td>{{ $order->created_at->format('M j, Y') }}</td><td>৳{{ number_format($order->total, 2) }}</td><td><span class="status-pill {{ $order->status }}">{{ ucfirst($order->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
@endsection
