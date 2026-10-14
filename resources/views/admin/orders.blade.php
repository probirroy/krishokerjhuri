@extends('admin.layout')

@section('title', 'Orders')
@section('heading', 'Orders')

@section('content')
    <div class="admin-page-head"><div><h1>Orders</h1><p>Review customer orders and update their fulfillment status.</p></div></div>
    <section class="admin-panel">
        @if($orders->isEmpty())
            <div class="admin-empty">No orders have been placed yet.</div>
        @else
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>Order</th><th>Customer</th><th>Delivery</th><th>Total</th><th>Payment</th><th>Status</th></tr></thead><tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong><small>{{ $order->created_at->format('M j, Y · g:i a') }}</small></td>
                        <td>{{ $order->customer_name }}<small>{{ $order->phone }}{{ $order->email ? ' · '.$order->email : '' }}</small></td>
                        <td>{{ $order->address }}, {{ $order->district }}</td>
                        <td>৳{{ number_format($order->total, 2) }}</td>
                        <td>Cash on delivery</td>
                        <td><form action="{{ route('admin.orders.update', $order) }}" method="POST" class="status-form">@csrf @method('PUT')<select name="status" aria-label="Order status">@foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select><button type="submit">Save</button></form></td>
                    </tr>
                    <tr class="order-line-row"><td colspan="6">@foreach($order->items as $item)<span>{{ $item->product_name }} × {{ $item->quantity }}</span>@endforeach</td></tr>
                @endforeach
            </tbody></table></div>
            <div class="admin-pagination">{{ $orders->links() }}</div>
        @endif
    </section>
@endsection
