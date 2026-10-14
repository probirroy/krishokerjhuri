<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer sign in | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-card">
        <a href="{{ route('home') }}" class="admin-login-brand">
            <span class="admin-logo">KJ</span>
            <span>{{ config('app.name') }}<small>Customer account</small></span>
        </a>
        <p class="eyebrow">Welcome back</p>
        <h1>Sign in</h1>
        <p class="login-help">Use your email address or mobile number.</p>
        @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
        @if(session('success'))<div class="form-success">{{ session('success') }}</div>@endif
        <form action="{{ route('login.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>Email or mobile number<input name="identifier" value="{{ old('identifier') }}" required autocomplete="username" autofocus></label>
            <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
            <button class="primary-button" type="submit">Sign in</button>
        </form>
        <p class="auth-switch">New to {{ config('app.name') }}? <a href="{{ route('register') }}">Create an account</a></p>
    </main>
</body>
</html>
