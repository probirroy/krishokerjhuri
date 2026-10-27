@extends('pages.layout')

@section('title', __($policyTitle))
@section('description', __($policyTitle).' | '.($siteSettings['brand_name'] ?? 'Krishoker Jhuri'))

@section('content')
    <section class="policy-page">
        <div class="container policy-container">
            <a class="article-back" href="{{ route('home') }}">← {{ __('Back to the store') }}</a>
            <p class="story-eyebrow">{{ __($policyEyebrow) }}</p>
            <h1>{{ __($policyTitle) }}</h1>
            @foreach($policySections as $section)
                <section class="policy-section">
                    <h2>{{ __($section['title']) }}</h2>
                    <p>{{ __($section['body']) }}</p>
                    @if($loop->last)
                        <a class="story-text-link" href="{{ route('contact') }}">{{ __('Contact our team') }} <span aria-hidden="true">→</span></a>
                    @endif
                </section>
            @endforeach
        </div>
    </section>
@endsection
