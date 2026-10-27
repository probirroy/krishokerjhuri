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
            <div class="panel-head"><div><h2>Featured products and contact</h2><p>Section labels and store contact details shown across the storefront.</p></div></div>
            <div class="form-grid">
                <label>Featured section label<input name="featured_eyebrow" value="{{ old('featured_eyebrow', $settings['featured_eyebrow'] ?? 'Featured Items') }}"></label>
                <label>Featured section heading<input name="featured_title" value="{{ old('featured_title', $settings['featured_title'] ?? 'Fresh picks for your kitchen') }}"></label>
                <label>Contact email<input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'hello@krishokerjhuri.com') }}"></label>
                <label>Contact phone<input name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" placeholder="+880 1XXX-XXXXXX"></label>
                <label>Store location<input name="store_location" value="{{ old('store_location', $settings['store_location'] ?? 'Dhaka, Bangladesh') }}"></label>
                <label>Opening hours<input name="store_hours" value="{{ old('store_hours', $settings['store_hours'] ?? 'Every day, 9:00 AM – 8:00 PM') }}"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>About page</h2><p>Tell visitors who you are, what you care about, and how your store works.</p></div></div>
            <div class="form-grid">
                <label>Eyebrow<input name="about_eyebrow" value="{{ old('about_eyebrow', $settings['about_eyebrow'] ?? 'Rooted in the community') }}"></label>
                <label>Page heading<input name="about_title" value="{{ old('about_title', $settings['about_title'] ?? 'Good food brings us closer to the farm') }}"></label>
                <label class="full-field">About story<textarea name="about_description" rows="6">{{ old('about_description', $settings['about_description'] ?? 'Krishoker Jhuri connects local growers with families looking for fresh, thoughtfully sourced food. We make it easier to discover seasonal produce, learn where it comes from, and support the people who grow it.') }}</textarea></label>
                <label>Hero button label<input name="about_button_label" value="{{ old('about_button_label', $settings['about_button_label'] ?? 'Explore the harvest') }}"></label>
                <label>About image<input type="file" name="about_image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
                <label>Or image URL<input type="url" name="about_image_url" value="{{ old('about_image_url', isset($settings['about_image_path']) && str_starts_with($settings['about_image_path'], 'http') ? $settings['about_image_path'] : '') }}" placeholder="https://…"></label>
                <label>Story section eyebrow<input name="about_detail_eyebrow" value="{{ old('about_detail_eyebrow', $settings['about_detail_eyebrow'] ?? 'A simpler way to shop') }}"></label>
                <label>Story section heading<input name="about_detail_title" value="{{ old('about_detail_title', $settings['about_detail_title'] ?? 'Know what’s on your table') }}"></label>
                <label class="full-field">Story section text<textarea name="about_detail_description" rows="3">{{ old('about_detail_description', $settings['about_detail_description'] ?? 'Browse what is available, choose what suits your kitchen, and have your order delivered to your door. We want every visit to feel clear, convenient, and connected to the people behind your food.') }}</textarea></label>
                <label>Values section eyebrow<input name="about_values_eyebrow" value="{{ old('about_values_eyebrow', $settings['about_values_eyebrow'] ?? 'What guides us') }}"></label>
                <label class="full-field">Values section heading<input name="about_values_title" value="{{ old('about_values_title', $settings['about_values_title'] ?? 'Good food, grounded in good relationships.') }}"></label>
                @foreach([1, 2, 3] as $valueNumber)
                    <label>Value {{ $valueNumber }} heading<input name="about_value_{{ $valueNumber }}_title" value="{{ old('about_value_'.$valueNumber.'_title', $settings['about_value_'.$valueNumber.'_title'] ?? ['Closer connections', 'Thoughtful choices', 'Community first'][$valueNumber - 1]) }}"></label>
                    <label>Value {{ $valueNumber }} description<textarea name="about_value_{{ $valueNumber }}_description" rows="2">{{ old('about_value_'.$valueNumber.'_description', $settings['about_value_'.$valueNumber.'_description'] ?? ['Make it easier for customers to discover the local growers behind their food.', 'Share clear product information so every household can shop with confidence.', 'Build a marketplace that brings neighborhood kitchens and farms together.'][$valueNumber - 1]) }}</textarea></label>
                @endforeach
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Our Farmers page</h2><p>Share your approach to working with growers. Edit the farmer feature above for the highlighted story.</p></div></div>
            <div class="form-grid">
                <label>Eyebrow<input name="farmers_page_eyebrow" value="{{ old('farmers_page_eyebrow', $settings['farmers_page_eyebrow'] ?? 'The people behind every harvest') }}"></label>
                <label>Page heading<input name="farmers_page_title" value="{{ old('farmers_page_title', $settings['farmers_page_title'] ?? 'Meet the hands that grow your food') }}"></label>
                <label class="full-field">Introduction<textarea name="farmers_page_description" rows="5">{{ old('farmers_page_description', $settings['farmers_page_description'] ?? 'We believe a stronger food system starts with respect for the people who grow our food. Our marketplace helps customers discover seasonal produce and gives local growers a direct way to reach nearby homes.') }}</textarea></label>
                <label>Hero button label<input name="farmers_button_label" value="{{ old('farmers_button_label', $settings['farmers_button_label'] ?? 'Shop local produce') }}"></label>
                <label class="full-field">Additional farmer story<textarea name="farmers_detail_description" rows="3">{{ old('farmers_detail_description', $settings['farmers_detail_description'] ?? 'Our focus is simple: make seasonal food easier to find and help customers appreciate the work that goes into growing it.') }}</textarea></label>
                <label>Grower invitation heading<input name="farmers_cta_title" value="{{ old('farmers_cta_title', $settings['farmers_cta_title'] ?? 'Are you a local grower?') }}"></label>
                <label>Grower invitation button<input name="farmers_cta_button_label" value="{{ old('farmers_cta_button_label', $settings['farmers_cta_button_label'] ?? 'Let’s talk') }}"></label>
                <label class="full-field">Grower invitation text<textarea name="farmers_cta_description" rows="2">{{ old('farmers_cta_description', $settings['farmers_cta_description'] ?? 'We’d love to hear about your farm, your seasonal harvest, and how we might work together.') }}</textarea></label>
                <label>Farmers page image<input type="file" name="farmers_page_image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
                <label>Or image URL<input type="url" name="farmers_page_image_url" value="{{ old('farmers_page_image_url', isset($settings['farmers_page_image_path']) && str_starts_with($settings['farmers_page_image_path'], 'http') ? $settings['farmers_page_image_path'] : '') }}" placeholder="https://…"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Blog and Contact page introductions</h2><p>Blog stories are managed separately from the content pages.</p></div></div>
            <div class="form-grid">
                <label>Blog page heading<input name="blog_page_title" value="{{ old('blog_page_title', $settings['blog_page_title'] ?? 'Notes from the field') }}"></label>
                <label class="full-field">Blog introduction<textarea name="blog_page_description" rows="3">{{ old('blog_page_description', $settings['blog_page_description'] ?? 'Seasonal inspiration, practical kitchen ideas, and stories from the people growing our food.') }}</textarea></label>
                <label>Empty journal heading<input name="blog_empty_title" value="{{ old('blog_empty_title', $settings['blog_empty_title'] ?? 'Fresh stories are growing') }}"></label>
                <label>Empty journal message<input name="blog_empty_description" value="{{ old('blog_empty_description', $settings['blog_empty_description'] ?? 'Check back soon for seasonal inspiration and notes from our community.') }}"></label>
                <label>Contact page heading<input name="contact_page_title" value="{{ old('contact_page_title', $settings['contact_page_title'] ?? 'We’re here to help') }}"></label>
                <label class="full-field">Contact introduction<textarea name="contact_page_description" rows="3">{{ old('contact_page_description', $settings['contact_page_description'] ?? 'Questions about an order, a product, or working with us? Send a note and our team will get back to you.') }}</textarea></label>
                <label>Contact eyebrow<input name="contact_eyebrow" value="{{ old('contact_eyebrow', $settings['contact_eyebrow'] ?? 'A real person is ready to help') }}"></label>
                <label>Contact form heading<input name="contact_form_title" value="{{ old('contact_form_title', $settings['contact_form_title'] ?? 'How can we help?') }}"></label>
            </div>
        </section>
        <section class="content-section">
            <div class="panel-head"><div><h2>Delivery charges</h2><p>Fees are selected automatically from the customer's district and saved with each order.</p></div></div>
            <div class="form-grid">
                <label>Inside Dhaka (৳)<input type="number" name="delivery_inside_dhaka" min="0" max="100000" step="0.01" value="{{ old('delivery_inside_dhaka', $settings['delivery_inside_dhaka'] ?? 70) }}" required></label>
                <label>Outside Dhaka (৳)<input type="number" name="delivery_outside_dhaka" min="0" max="100000" step="0.01" value="{{ old('delivery_outside_dhaka', $settings['delivery_outside_dhaka'] ?? 150) }}" required></label>
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
