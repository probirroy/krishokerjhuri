@extends('pages.layout')

@section('title', $siteSettings['farmers_page_title'] ?? 'Our farmers')
@section('description', \Illuminate\Support\Str::limit($siteSettings['farmers_page_description'] ?? 'Meet the growers behind every harvest.', 160))

@section('content')
    <section class="story-hero story-hero-farmers">
        <div class="story-hero-orb story-hero-orb-one"></div><div class="story-hero-orb story-hero-orb-two"></div>
        <div class="container story-hero-inner">
            <p class="story-eyebrow">{{ $siteSettings['farmers_page_eyebrow'] ?? 'The people behind every harvest' }}</p>
            <h1>{{ $siteSettings['farmers_page_title'] ?? 'Meet the hands that grow your food' }}</h1>
            <p>{{ $siteSettings['farmers_page_description'] ?? 'We believe a stronger food system starts with respect for the people who grow our food. Our marketplace helps customers discover seasonal produce and gives local growers a direct way to reach nearby homes.' }}</p>
            <a class="story-button" href="{{ route('shop') }}">{{ $siteSettings['farmers_button_label'] ?? 'Shop local produce' }} <span aria-hidden="true">→</span></a>
        </div>
        <span class="story-hero-mark" aria-hidden="true">✳</span>
    </section>
    <section class="story-section">
        <div class="container story-split">
            <div class="story-image-frame">
                <img src="{{ $siteSettings['farmers_page_image_path'] ?? $farmerFeature?->image_url ?? asset('images/home-harvest.jpg') }}" alt="Local farming and fresh seasonal crops" loading="lazy">
                <span class="story-image-note">Grown with care, shared with care</span>
            </div>
            <article class="story-copy">
                <p class="story-eyebrow">{{ $farmerFeature?->subtitle ?? 'A fairer connection' }}</p>
                <h2>{{ $farmerFeature?->title ?? 'Supporting local growers' }}</h2>
                <p>{{ $farmerFeature?->description ?? 'We want local growers to have a direct connection with the families who enjoy their harvest. Every order is an opportunity to bring the community closer to its food.' }}</p>
                <p>{{ $siteSettings['farmers_detail_description'] ?? 'Our focus is simple: make seasonal food easier to find and help customers appreciate the work that goes into growing it.' }}</p>
                <a class="story-text-link" href="{{ route('contact') }}">Interested in working with us? <span aria-hidden="true">→</span></a>
            </article>
        </div>
    </section>
    <section class="story-values">
        <div class="container farmer-invitation">
            <p class="story-eyebrow">Grow with our community</p>
            <h2>{{ $siteSettings['farmers_cta_title'] ?? 'Are you a local grower?' }}</h2>
            <p>{{ $siteSettings['farmers_cta_description'] ?? 'We’d love to hear about your farm, your seasonal harvest, and how we might work together.' }}</p>
            <a class="story-button story-button-dark" href="{{ route('contact') }}">{{ $siteSettings['farmers_cta_button_label'] ?? 'Let’s talk' }} <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
