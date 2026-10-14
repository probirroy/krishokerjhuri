<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Krishoker Jhuri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand"><span class="admin-logo">KJ</span><span>Krishoker Jhuri<small>Store admin</small></span></a>
            <nav aria-label="Admin navigation">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a>
                <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Products</a>
                <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders') }}">Orders</a>
                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Admin access</a>
                <a class="{{ request()->routeIs('admin.content*') ? 'active' : '' }}" href="{{ route('admin.content') }}">Store content</a>
            </nav>
            <div class="admin-sidebar-bottom">
                <a href="{{ route('home') }}" target="_blank" rel="noopener">View storefront ↗</a>
                <form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit">Sign out</button></form>
            </div>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <div><span class="admin-kicker">Store management</span><strong>@yield('heading', 'Overview')</strong></div>
                <div class="admin-top-actions"><span>{{ auth()->user()->name }}</span><button class="theme-toggle" type="button" aria-label="Switch theme" title="Switch theme">◐</button></div>
            </header>
            <section class="admin-content">
                @if(session('success'))<div class="form-success">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
