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
            <label>SKU<input name="sku" value="{{ old('sku', $product->sku) }}" maxlength="80" placeholder="Auto-assigned if left blank"><small>Unique internal stock-keeping code. Leave blank to assign one automatically.</small></label>
            <label>Barcode value<input name="barcode" value="{{ old('barcode', $product->barcode) }}" maxlength="120" placeholder="Enter existing UPC, EAN, GTIN, or other code"><small>Enter the number/text printed on your product. Do not enter a made-up retail barcode.</small></label>
            <label>Price (৳)<input type="number" name="price" min="0" step="0.01" value="{{ old('price', $product->price) }}" required></label>
            <label>Compare-at price (৳)<input type="number" name="compare_price" min="0" step="0.01" value="{{ old('compare_price', $product->compare_price) }}"></label>
            <label>Unit<input name="unit" value="{{ old('unit', $product->unit ?: 'kg') }}" required placeholder="kg, bundle, box"></label>
            <label>Stock quantity<input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required></label>
            <label>Badge<input name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Fresh, Seasonal, Best seller"></label>
            <label>Main image upload<input type="file" name="image" accept="image/*"><small>Used as the cover photo · JPG, PNG, or WebP · max 6 MB</small></label>
            <label>Additional product images<input type="file" name="images[]" accept="image/*" multiple><small>Select up to 10 more photos · max 6 MB each</small></label>
            <label class="full-field">Or image URL<input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://…"></label>
            <label class="full-field">Short description<textarea name="short_description" rows="2" maxlength="1000" placeholder="A clear one-sentence summary for product cards and search results">{{ old('short_description', $product->short_description) }}</textarea><small>Highlight the main product benefit, format, and typical use. Aim for about 160–300 characters; up to 1,000 are accepted.</small></label>
            <label class="full-field">Product description<textarea name="description" rows="8" maxlength="10000" placeholder="Describe the product, flavour or characteristics, serving or usage ideas, unit/pack size, and storage or preparation guidance. Use line breaks to organize the copy.">{{ old('description', $product->description) }}</textarea><small>Write specific, accurate buyer-focused details. Avoid unsupported health, organic-certification, origin, or shelf-life claims.</small></label>
        </div>
        <section class="product-seo-fields" aria-labelledby="product-seo-heading">
            <div>
                <h2 id="product-seo-heading">Search &amp; social preview</h2>
                <p>Optional product-specific metadata. Leave blank to use the product name and description automatically.</p>
            </div>
            <label>SEO title<input name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" maxlength="70" placeholder="Product name | Krishoker Jhuri"><small>Recommended: under 60 characters; the limit is 70.</small></label>
            <label>Meta description<textarea name="seo_description" rows="3" maxlength="320" placeholder="A concise, accurate summary that helps shoppers understand this product.">{{ old('seo_description', $product->seo_description) }}</textarea><small>Recommended: about 150–160 characters; the limit is 320.</small></label>
            <label>Meta keywords<input name="seo_keywords" value="{{ old('seo_keywords', $product->seo_keywords) }}" maxlength="500" placeholder="product name, variety, use, relevant local search terms"><small>Comma-separated; optional. Search engines may ignore this tag.</small></label>
        </section>
        @if($product->galleryImages())
            <div class="product-admin-gallery">
                <span>Current product images</span>
                <div class="product-admin-gallery-images">
                    @foreach($product->galleryImages() as $image)
                        <img src="{{ $image }}" alt="{{ $product->name }}">
                    @endforeach
                </div>
            </div>
        @endif
        <div class="form-options">
            <label class="check-row"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Show on homepage</label>
            <label class="check-row"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> Published in shop</label>
        </div>
        <button type="submit" class="primary-button">{{ $product->exists ? 'Save product' : 'Create product' }}</button>
    </form>
@endsection
