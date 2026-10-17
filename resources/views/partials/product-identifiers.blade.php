@if($product->sku || $product->barcode)
    <dl class="product-identifiers">
        @if($product->sku)
            <div><dt>{{ __('SKU') }}</dt><dd>{{ $product->sku }}</dd></div>
        @endif
        @if($product->barcode)
            <div><dt>{{ __('Barcode') }}</dt><dd>{{ $product->barcode }}</dd></div>
        @endif
    </dl>
@endif
