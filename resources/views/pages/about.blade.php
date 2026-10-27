@extends('pages.layout')

@section('title', $siteSettings['about_title'] ?? 'About us')
@section('description', \Illuminate\Support\Str::limit($siteSettings['about_description'] ?? 'A closer connection between local farms and your table.', 160))

@section('content')
    <section class="story-hero">
        <div class="story-hero-orb story-hero-orb-one"></div><div class="story-hero-orb story-hero-orb-two"></div>
        <div class="container story-hero-inner">
            <p class="story-eyebrow">{{ $siteSettings['about_eyebrow'] ?? 'Rooted in the community' }}</p>
            <h1>{{ $siteSettings['about_title'] ?? 'Good food brings us closer to the farm' }}</h1>
            <p>{{ $siteSettings['about_description'] ?? 'Krishoker Jhuri connects local growers with families looking for fresh, thoughtfully sourced food. We make it easier to discover seasonal produce, learn where it comes from, and support the people who grow it.' }}</p>
            <a class="story-button" href="{{ route('shop') }}">{{ $siteSettings['about_button_label'] ?? 'Explore the harvest' }} <span aria-hidden="true">→</span></a>
        </div>
        <span class="story-hero-mark" aria-hidden="true">KJ</span>
    </section>
    <section class="story-section">
        <div class="container story-split">
            <div class="story-image-frame">
                <img src="{{ $siteSettings['about_image_path'] ?? asset('images/home-harvest.jpg') }}" alt="Fresh produce and a connection to local farms" loading="lazy">
                <span class="story-image-note">From local growers to your home</span>
            </div>
            <article class="story-copy">
                <p class="story-eyebrow">{{ $siteSettings['about_detail_eyebrow'] ?? 'A simpler way to shop' }}</p>
                <h2>{{ $siteSettings['about_detail_title'] ?? 'Know what’s on your table' }}</h2>
                <p>{{ $siteSettings['about_description'] ?? 'We bring local growers and neighborhood families closer together.' }}</p>
                <p>{{ $siteSettings['about_detail_description'] ?? 'Browse what is available, choose what suits your kitchen, and have your order delivered to your door. We want every visit to feel clear, convenient, and connected to the people behind your food.' }}</p>
                <a class="story-text-link" href="{{ route('farmers') }}">Get to know our farmers <span aria-hidden="true">→</span></a>
            </article>
        </div>
    </section>
    <section class="story-values">
        <div class="container">
            <div class="story-section-heading"><p class="story-eyebrow">{{ $siteSettings['about_values_eyebrow'] ?? 'What guides us' }}</p><h2>{{ $siteSettings['about_values_title'] ?? 'Good food, grounded in good relationships.' }}</h2></div>
            <div class="story-value-grid">
                @foreach([1, 2, 3] as $valueNumber)
                    @php
                        $valueTitles = ['Closer connections', 'Thoughtful choices', 'Community first'];
                        $valueDescriptions = [
                            'Make it easier for customers to discover the local growers behind their food.',
                            'Share clear product information so every household can shop with confidence.',
                            'Build a marketplace that brings neighborhood kitchens and farms together.',
                        ];
                    @endphp
                    <article><span>{{ sprintf('%02d', $valueNumber) }}</span><h3>{{ $siteSettings['about_value_'.$valueNumber.'_title'] ?? $valueTitles[$valueNumber - 1] }}</h3><p>{{ $siteSettings['about_value_'.$valueNumber.'_description'] ?? $valueDescriptions[$valueNumber - 1] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
