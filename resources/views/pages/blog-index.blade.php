@extends('pages.layout')

@section('title', $siteSettings['blog_page_title'] ?? 'Field notes')
@section('description', $siteSettings['blog_page_description'] ?? 'Seasonal inspiration and stories from the farm.')

@section('content')
    <section class="story-hero story-hero-journal">
        <div class="story-hero-orb story-hero-orb-one"></div><div class="story-hero-orb story-hero-orb-two"></div>
        <div class="container story-hero-inner">
            <p class="story-eyebrow">The Krishoker Jhuri journal</p>
            <h1>{{ $siteSettings['blog_page_title'] ?? 'Notes from the field' }}</h1>
            <p>{{ $siteSettings['blog_page_description'] ?? 'Seasonal inspiration, practical kitchen ideas, and stories from the people growing our food.' }}</p>
        </div>
        <span class="story-hero-mark" aria-hidden="true">✎</span>
    </section>
    <section class="journal-section">
        <div class="container">
            @if($posts->isNotEmpty())
                <div class="journal-grid">
                    @foreach($posts as $post)
                        <article class="journal-card">
                            <a class="journal-card-image" href="{{ route('blog.show', $post->slug) }}">
                                <img src="{{ $post->image_path ?? asset('images/home-harvest.jpg') }}" alt="{{ $post->title }}" loading="lazy">
                                <span>Read story <span aria-hidden="true">↗</span></span>
                            </a>
                            <div class="journal-card-copy">
                                <p class="journal-date">{{ ($post->published_at ?? $post->created_at)->format('F j, Y') }}</p>
                                <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                                <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 180) }}</p>
                                <a class="story-text-link" href="{{ route('blog.show', $post->slug) }}">Read the story <span aria-hidden="true">→</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="journal-pagination">{{ $posts->links() }}</div>
            @else
                <div class="journal-empty"><span aria-hidden="true">✳</span><h2>{{ $siteSettings['blog_empty_title'] ?? 'Fresh stories are growing' }}</h2><p>{{ $siteSettings['blog_empty_description'] ?? 'Check back soon for seasonal inspiration and notes from our community.' }}</p><a class="story-button story-button-dark" href="{{ route('shop') }}">Explore the shop <span aria-hidden="true">→</span></a></div>
            @endif
        </div>
    </section>
@endsection
