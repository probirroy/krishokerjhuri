@extends('admin.layout')

@section('title', 'Products')
@section('heading', 'Products')

@section('content')
    <div class="admin-page-head">
        <div><h1>Products</h1><p>Add and update your catalog, prices, images, and stock.</p></div>
        <a href="{{ route('admin.products.create') }}" class="primary-button">＋ Add product</a>
    </div>
    <section class="admin-panel">
        @if($products->isEmpty())
            <div class="admin-empty">No products found. Add your first product to publish it in the shop.</div>
        @else
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Visibility</th><th>Actions</th></tr></thead><tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="product-table-cell"><img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=120&q=70' }}" alt=""><div><strong>{{ $product->name }}</strong><small>{{ $product->slug }}</small></div></td>
                        <td>{{ $product->category?->name }}</td><td>৳{{ number_format($product->price, 2) }} / {{ $product->unit }}</td><td>{{ $product->stock }}</td>
                        <td><span class="status-pill {{ $product->is_active ? 'delivered' : 'cancelled' }}">{{ $product->is_active ? 'Published' : 'Hidden' }}</span></td>
                        <td class="table-actions"><a href="{{ route('admin.products.edit', $product) }}">Edit</a><form action="{{ route('admin.products.delete', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td>
                    </tr>
                @endforeach
            </tbody></table></div>
            <div class="admin-pagination">{{ $products->links() }}</div>
        @endif
    </section>
@endsection
