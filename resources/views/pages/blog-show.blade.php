@extends('pages.layout')

@section('title', $post->title)
@section('description', $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 160))

@section('content')
    <article class="article-page">
        <div class="container article-container">
            <a class="article-back" href="{{ route('blog.index') }}">← Back to the journal</a>
            <header class="article-header">
                <p class="story-eyebrow">From the Krishoker Jhuri journal</p>
                <h1>{{ $post->title }}</h1>
                @if($post->excerpt)<p class="article-excerpt">{{ $post->excerpt }}</p>@endif
                <p class="journal-date">{{ ($post->published_at ?? $post->created_at)->format('F j, Y') }}</p>
            </header>
            @if($post->image_path)
                <div class="article-cover"><img src="{{ $post->image_path }}" alt="{{ $post->title }}"></div>
            @endif
            <div class="article-body">{!! nl2br(e($post->body)) !!}</div>
            <a class="story-text-link article-return" href="{{ route('blog.index') }}">More stories <span aria-hidden="true">→</span></a>
        </div>
    </article>
@endsection
