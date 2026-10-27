@extends('pages.layout')

@section('title', $siteSettings['contact_page_title'] ?? 'Contact us')
@section('description', $siteSettings['contact_page_description'] ?? 'Questions? We are here to help.')

@section('content')
    <section class="story-hero story-hero-contact">
        <div class="story-hero-orb story-hero-orb-one"></div><div class="story-hero-orb story-hero-orb-two"></div>
        <div class="container story-hero-inner">
            <p class="story-eyebrow">{{ $siteSettings['contact_eyebrow'] ?? 'A real person is ready to help' }}</p>
            <h1>{{ $siteSettings['contact_page_title'] ?? 'We’re here to help' }}</h1>
            <p>{{ $siteSettings['contact_page_description'] ?? 'Questions about an order, a product, or working with us? Send a note and our team will get back to you.' }}</p>
        </div>
        <span class="story-hero-mark" aria-hidden="true">✉</span>
    </section>
    <section class="contact-section">
        <div class="container contact-layout">
            <div class="contact-details">
                <p class="story-eyebrow">Get in touch</p>
                <h2>Let’s start a conversation.</h2>
                <p class="contact-intro">Choose whichever way works best for you. For order questions, include your order number so we can help faster.</p>
                @if(!empty($siteSettings['contact_email']))
                    <a class="contact-detail-card" href="mailto:{{ $siteSettings['contact_email'] }}"><span class="contact-detail-icon">✉</span><span><small>Email us</small><strong>{{ $siteSettings['contact_email'] }}</strong></span><span class="contact-arrow">↗</span></a>
                @endif
                @if(!empty($siteSettings['contact_phone']))
                    <a class="contact-detail-card" href="tel:{{ preg_replace('/[^+0-9]/', '', $siteSettings['contact_phone']) }}"><span class="contact-detail-icon">☎</span><span><small>Call us</small><strong>{{ $siteSettings['contact_phone'] }}</strong></span><span class="contact-arrow">↗</span></a>
                @endif
                @if(!empty($siteSettings['store_location']))
                    <div class="contact-detail-card"><span class="contact-detail-icon">⌖</span><span><small>Find us</small><strong>{{ $siteSettings['store_location'] }}</strong></span></div>
                @endif
                @if(!empty($siteSettings['store_hours']))
                    <div class="contact-detail-card"><span class="contact-detail-icon">◷</span><span><small>Store hours</small><strong>{{ $siteSettings['store_hours'] }}</strong></span></div>
                @endif
            </div>
            <div class="contact-form-card">
                <p class="story-eyebrow">Send a message</p>
                <h2>{{ $siteSettings['contact_form_title'] ?? 'How can we help?' }}</h2>
                @if(session('success'))<div class="contact-success" role="status">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="contact-error" role="alert">{{ $errors->first() }}</div>@endif
                <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                    @csrf
                    <label>Your name<input name="name" autocomplete="name" value="{{ old('name') }}" maxlength="120" required></label>
                    <label>Email address<input type="email" name="email" autocomplete="email" value="{{ old('email') }}" maxlength="180" required></label>
                    <label>Subject<input name="subject" value="{{ old('subject') }}" maxlength="180" required></label>
                    <label>Your message<textarea name="message" rows="5" maxlength="5000" required>{{ old('message') }}</textarea></label>
                    <button type="submit" class="story-button story-button-dark">Send message <span aria-hidden="true">→</span></button>
                </form>
            </div>
        </div>
    </section>
@endsection
