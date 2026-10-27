<footer class="site-footer page-footer">
    <div class="container page-footer-grid">
        <div class="page-footer-brand">
            <a href="{{ route('home') }}">{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</a>
            <p>{{ __($siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}</p>
            <span>&copy; {{ now()->year }} {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}. {{ __('All rights reserved.') }}</span>
        </div>
        <div>
            <h2>Explore</h2>
            <nav class="page-footer-links" aria-label="Footer navigation">
                <a href="{{ route('about') }}">About us</a>
                <a href="{{ route('farmers') }}">Our farmers</a>
                <a href="{{ route('blog.index') }}">Journal</a>
                <a href="{{ route('contact') }}">Contact</a>
                <a href="{{ route('shop') }}">Shop</a>
            </nav>
        </div>
        <div>
            <h2>Say hello</h2>
            @if(!empty($siteSettings['store_location']))<p>{{ $siteSettings['store_location'] }}</p>@endif
            @if(!empty($siteSettings['contact_email']))<a href="mailto:{{ $siteSettings['contact_email'] }}">{{ $siteSettings['contact_email'] }}</a>@endif
            @if(!empty($siteSettings['contact_phone']))<a href="tel:{{ preg_replace('/[^+0-9]/', '', $siteSettings['contact_phone']) }}">{{ $siteSettings['contact_phone'] }}</a>@endif
            @if(!empty($siteSettings['store_hours']))<p>{{ $siteSettings['store_hours'] }}</p>@endif
            <div class="page-footer-social">
                @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $key => $label)
                    @if(!empty($siteSettings["social_{$key}_url"]))
                        <a href="{{ $siteSettings["social_{$key}_url"] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __($label) }}" title="{{ __($label) }}">
                            @if($key === 'facebook')
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h3V4h-3c-3 0-5 2-5 5v2H6v4h3v5h4v-5h3l1-4h-4V9c0-.7.3-1 1-1Z"/></svg>
                            @elseif($key === 'instagram')
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.8" r=".7"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2.5" y="5" width="19" height="14" rx="4"/><path d="m10 9 5 3-5 3z"/></svg>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</footer>
