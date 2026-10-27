<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Customer sign in') }} | {{ __(config('app.name')) }}</title>
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
        <p class="eyebrow">{{ __('Welcome back') }}</p>
        <h1>{{ __('Sign in') }}</h1>
        <p class="login-help">{{ __('Use your email address or mobile number.') }}</p>
        @if($errors->any())<div class="form-errors">{{ __($errors->first()) }}</div>@endif
        @if(session('success'))<div class="form-success">{{ __(session('success')) }}</div>@endif
        <form action="{{ route('login.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>{{ __('Email or mobile number') }}<input name="identifier" value="{{ old('identifier') }}" required autocomplete="username" autofocus></label>
            <label>{{ __('Password') }}<input type="password" name="password" required autocomplete="current-password"></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> {{ __('Remember me') }}</label>
            <button class="primary-button" type="submit">{{ __('Sign in') }}</button>
        </form>
        <p class="auth-switch">{{ __('New to :name?', ['name' => __(config('app.name'))]) }} <a href="{{ route('register') }}">{{ __('Create an account') }}</a></p>
    </main>
</body>
</html>
