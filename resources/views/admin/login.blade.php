<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin sign in | Krishoker Jhuri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-login-page">
    <main class="admin-login-card">
        <a href="{{ route('home') }}" class="admin-login-brand"><span class="admin-logo">KJ</span><span>Krishoker Jhuri<small>Administrator access</small></span></a>
        <p class="eyebrow">Store management</p>
        <h1>Welcome back</h1>
        <p class="login-help">Sign in with your administrator account to manage your store.</p>
        @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
        <form action="{{ route('admin.login.submit') }}" method="POST" class="admin-form">
            @csrf
            <label>Email address<input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus></label>
            <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
            <button class="primary-button" type="submit">Sign in to admin</button>
        </form>
        <a class="back-link" href="{{ route('home') }}">← Back to store</a>
    </main>
</body>
</html>
