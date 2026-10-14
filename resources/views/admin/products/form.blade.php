@extends('admin.layout')

@section('title', $product->exists ? 'Edit product' : 'Add product')
@section('heading', 'Products')

@section('content')
    <div class="admin-page-head">
        <div><h1>{{ $product->exists ? 'Edit product' : 'Add a product' }}</h1><p>Product images, pricing, details, and inventory.</p></div>
        <a class="secondary-button" href="{{ route('admin.products.index') }}">← Back to products</a>
    </div>
    <form class="admin-panel admin-form product-edit-form" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif
        <div class="form-grid">
            <label>Product name<input name="name" value="{{ old('name', $product->name) }}" required maxlength="180"></label>
            <label>Category<select name="category_id" required><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
            <label>Price (৳)<input type="number" name="price" min="0" step="0.01" value="{{ old('price', $product->price) }}" required></label>
            <label>Compare-at price (৳)<input type="number" name="compare_price" min="0" step="0.01" value="{{ old('compare_price', $product->compare_price) }}"></label>
            <label>Unit<input name="unit" value="{{ old('unit', $product->unit ?: 'kg') }}" required placeholder="kg, bundle, box"></label>
            <label>Stock quantity<input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required></label>
            <label>Badge<input name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Fresh, Seasonal, Best seller"></label>
            <label>Image upload<input type="file" name="image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
            <label class="full-field">Or image URL<input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://…"></label>
            <label class="full-field">Short description<textarea name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea></label>
            <label class="full-field">Product details<textarea name="description" rows="5">{{ old('description', $product->description) }}</textarea></label>
        </div>
        @if($product->image_url)<div class="image-preview"><span>Current image</span><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div>@endif
        <div class="form-options">
            <label class="check-row"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Show on homepage</label>
            <label class="check-row"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> Published in shop</label>
        </div>
        <button type="submit" class="primary-button">{{ $product->exists ? 'Save product' : 'Create product' }}</button>
    </form>
@endsection
