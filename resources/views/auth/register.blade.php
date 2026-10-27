<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Create account') }} | {{ __(config('app.name')) }}</title>
    @include('partials.pwa-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-card">
        @include('partials.language-switch')
        <a href="{{ route('home') }}" class="admin-login-brand">
            <span class="admin-logo">KJ</span>
            <span>{{ __(config('app.name')) }}<small>{{ __('Customer account') }}</small></span>
        </a>
        <p class="eyebrow">{{ __('Join our community') }}</p>
        <h1>{{ __('Create account') }}</h1>
        <p class="login-help">{{ __('Add an email, mobile number, or both. You can use either to sign in.') }}</p>
        @if($errors->any())<div class="form-errors">{{ __($errors->first()) }}</div>@endif
        <form action="{{ route('register.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>{{ __('Full name') }}<input name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label>{{ __('Email address (optional)') }}<input type="email" name="email" value="{{ old('email') }}" autocomplete="email"></label>
            <label>{{ __('Mobile number (optional)') }}<input type="tel" name="mobile" value="{{ old('mobile') }}" autocomplete="tel" placeholder="{{ __('01XXXXXXXXX') }}"></label>
            <label>{{ __('Password') }}<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
            <label>{{ __('Confirm password') }}<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></label>
            <button class="primary-button" type="submit">{{ __('Create account') }}</button>
        </form>
        <p class="auth-switch">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign in') }}</a></p>
    </main>
</body>
</html>
