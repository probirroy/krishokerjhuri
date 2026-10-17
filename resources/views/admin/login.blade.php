<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin sign in') }} | Krishoker Jhuri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-card">
        @include('partials.language-switch')
        <a href="{{ route('home') }}" class="admin-login-brand"><span class="admin-logo">KJ</span><span>Krishoker Jhuri<small>{{ __('Administrator access') }}</small></span></a>
        <p class="eyebrow">{{ __('Store management') }}</p>
        <h1>{{ __('Welcome back') }}</h1>
        <p class="login-help">{{ __('Sign in with your administrator account to manage your store.') }}</p>
        @if($errors->any())<div class="form-errors">{{ __($errors->first()) }}</div>@endif
        <form action="{{ route('admin.login.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>{{ __('Email address') }}<input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus></label>
            <label>{{ __('Password') }}<input type="password" name="password" required autocomplete="current-password"></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> {{ __('Remember me') }}</label>
            <button class="primary-button" type="submit">{{ __('Sign in to admin') }}</button>
        </form>
        <a class="back-link" href="{{ route('home') }}">← {{ __('Back to store') }}</a>
    </main>
</body>
</html>
