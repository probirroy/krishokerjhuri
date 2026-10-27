<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ $siteSettings['brand_name'] ?? 'Krishoker Jhuri' }}</title>
    <meta name="description" content="@yield('description', $siteSettings['tagline'] ?? 'Fresh from local farms, delivered to your door.')">
    @include('partials.pwa-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="store-page">
    @include('partials.store-header')
    <main>@yield('content')</main>
    @include('partials.store-footer')
</body>
</html>
