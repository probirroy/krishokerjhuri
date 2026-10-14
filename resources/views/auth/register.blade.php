<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-card">
        <a href="{{ route('home') }}" class="admin-login-brand">
            <span class="admin-logo">KJ</span>
            <span>{{ config('app.name') }}<small>Customer account</small></span>
        </a>
        <p class="eyebrow">Join our community</p>
        <h1>Create account</h1>
        <p class="login-help">Add an email, mobile number, or both. You can use either to sign in.</p>
        @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
        <form action="{{ route('register.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>Full name<input name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label>Email address (optional)<input type="email" name="email" value="{{ old('email') }}" autocomplete="email"></label>
            <label>Mobile number (optional)<input type="tel" name="mobile" value="{{ old('mobile') }}" autocomplete="tel" placeholder="+880 1XXXXXXXXX"></label>
            <label>Password<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
            <label>Confirm password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></label>
            <button class="primary-button" type="submit">Create account</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </main>
</body>
</html>
