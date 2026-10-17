@extends('admin.layout')

@section('title', 'Store content')
@section('heading', 'Store content')

@section('content')
    <div class="admin-page-head"><div><h1>Store content</h1><p>Edit your homepage branding, hero, farmer panel, and service benefits.</p></div></div>
    <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data" class="admin-panel admin-form content-form">
        @csrf @method('PUT')
        <section class="content-section">
            <div class="panel-head"><div><h2>Brand identity</h2><p>Name and logo shown in the storefront.</p></div></div>
            <div class="form-grid">
                <label>Store name<input name="brand_name" value="{{ old('brand_name', $settings['brand_name'] ?? 'Krishoker Jhuri') }}" required></label>
                <label>Tagline<input name="tagline" value="{{ old('tagline', $settings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}"></label>
                <label>Logo image<input type="file" name="logo" accept="image/*"><small>Transparent PNG or WebP image · max 2 MB</small></label>
                @if(!empty($settings['logo_path']))<div class="logo-preview"><span>Current logo</span><img src="{{ $settings['logo_path'] }}" alt="Current store logo"></div>@endif
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Homepage hero</h2><p>Headline, description, and banner image.</p></div></div>
            <div class="form-grid">
                <label>Eyebrow<input name="hero_eyebrow" value="{{ old('hero_eyebrow', $settings['hero_eyebrow'] ?? 'Organic Vegetables') }}"></label>
                <label>Heading<input name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Krishoker Jhuri') }}" required></label>
                <label>Supporting heading<input name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Fresh, Natural, Healthy') }}"></label>
                <label>Shop button label<input name="hero_button_label" value="{{ old('hero_button_label', $settings['hero_button_label'] ?? 'Shop Now') }}"></label>
                <label>Floating banner message<input name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge'] ?? 'Pure Organic Fresh') }}"></label>
                <label class="full-field">Description<textarea name="hero_description" rows="3">{{ old('hero_description', $settings['hero_description'] ?? 'Bringing farm-fresh organic vegetables directly from local farmers to your home.') }}</textarea></label>
                <label>Banner image upload<input type="file" name="hero_image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
                <label>Or image URL<input type="url" name="hero_image_url" value="{{ old('hero_image_url', isset($settings['hero_image_path']) && str_starts_with($settings['hero_image_path'], 'http') ? $settings['hero_image_path'] : '') }}" placeholder="https://…"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Farmer promotion</h2><p>Text and image for the farmer feature panel.</p></div></div>
            <div class="form-grid">
                <label>Section label<input name="promo_subtitle" value="{{ old('promo_subtitle', $farmerFeature->subtitle ?? 'Our Farmers') }}"></label>
                <label>Heading<input name="promo_title" value="{{ old('promo_title', $farmerFeature->title ?? 'Fresh from our local farms') }}"></label>
                <label class="full-field">Description<textarea name="promo_description" rows="3">{{ old('promo_description', $farmerFeature->description ?? '') }}</textarea></label>
                <label>Button label<input name="promo_cta_label" value="{{ old('promo_cta_label', $farmerFeature->cta_label ?? 'Meet the farmers') }}"></label>
                <label>Farmer image<input type="file" name="promo_image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Featured products and contact</h2><p>Section labels and store contact details used in the footer.</p></div></div>
            <div class="form-grid">
                <label>Featured section label<input name="featured_eyebrow" value="{{ old('featured_eyebrow', $settings['featured_eyebrow'] ?? 'Featured Items') }}"></label>
                <label>Featured section heading<input name="featured_title" value="{{ old('featured_title', $settings['featured_title'] ?? 'Fresh picks for your kitchen') }}"></label>
                <label>Contact email<input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'hello@krishokerjhuri.com') }}"></label>
                <label>Store location<input name="store_location" value="{{ old('store_location', $settings['store_location'] ?? 'Dhaka, Bangladesh') }}"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Social media links</h2><p>Only links you add here will appear as clickable icons in the storefront footer.</p></div></div>
            <div class="form-grid">
                <label>Facebook URL<input type="url" name="social_facebook_url" value="{{ old('social_facebook_url', $settings['social_facebook_url'] ?? '') }}" placeholder="https://www.facebook.com/your-page"></label>
                <label>Instagram URL<input type="url" name="social_instagram_url" value="{{ old('social_instagram_url', $settings['social_instagram_url'] ?? '') }}" placeholder="https://www.instagram.com/your-account"></label>
                <label>YouTube URL<input type="url" name="social_youtube_url" value="{{ old('social_youtube_url', $settings['social_youtube_url'] ?? '') }}" placeholder="https://www.youtube.com/@your-channel"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Service benefits</h2><p>Only publish claims you can substantiate.</p></div></div>
            @foreach($benefits as $benefit)
                <div class="benefit-edit-row">
                    <label>Title<input name="benefits[{{ $benefit->id }}][title]" value="{{ old('benefits.'.$benefit->id.'.title', $benefit->title) }}" required></label>
                    <label>Description<input name="benefits[{{ $benefit->id }}][subtitle]" value="{{ old('benefits.'.$benefit->id.'.subtitle', $benefit->subtitle) }}"></label>
                    <label class="check-row"><input type="hidden" name="benefits[{{ $benefit->id }}][is_active]" value="0"><input type="checkbox" name="benefits[{{ $benefit->id }}][is_active]" value="1" @checked($benefit->is_active)> Visible</label>
                </div>
            @endforeach
        </section>
        <button class="primary-button" type="submit">Save store content</button>
    </form>
@endsection
