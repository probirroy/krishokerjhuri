<footer class="site-footer page-footer">
    <nav class="footer-policy-bar" aria-label="{{ __('Store policies') }}">
        <a class="footer-policy-link" href="{{ route('policies.terms') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.75h7l4.25 4.5v12H7a2 2 0 0 1-2-2v-12a2.5 2.5 0 0 1 2-2.5Z"/><path d="M14 4v5h4M8 13h8M8 16.5h8"/></svg>
            <span>{{ __('Terms and conditions') }}</span>
        </a>
        <a class="footer-policy-link" href="{{ route('policies.returns') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7 4 12l5 5"/><path d="M4.5 12H16a4 4 0 0 1 0 8h-2"/></svg>
            <span>{{ __('Returns and order issues') }}</span>
        </a>
        <a class="footer-policy-link" href="{{ route('policies.support') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13v-2a8 8 0 0 1 16 0v2"/><rect x="3" y="12" width="4" height="7" rx="2"/><rect x="17" y="12" width="4" height="7" rx="2"/><path d="M18 19a4 4 0 0 1-4 3h-2"/></svg>
            <span>{{ __('Support policy') }}</span>
        </a>
        <a class="footer-policy-link" href="{{ route('policies.privacy') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16.5h.01"/></svg>
            <span>{{ __('Privacy policy') }}</span>
        </a>
    </nav>

    <div class="container page-footer-grid">
        <div class="page-footer-brand">
            <a href="{{ route('home') }}">{{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}</a>
            <p>{{ __($siteSettings['tagline'] ?? 'Fresh from Farmers • Pure for You') }}</p>
            <span>{{ __('Fresh from local farms, delivered to your door.') }}</span>
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

        <div>
            <h2>{{ __('Policy') }}</h2>
            <nav class="page-footer-links" aria-label="{{ __('Policy links') }}">
                <a href="{{ route('policies.terms') }}">{{ __('Terms and conditions') }}</a>
                <a href="{{ route('policies.returns') }}">{{ __('Returns and order issues') }}</a>
                <a href="{{ route('policies.support') }}">{{ __('Support policy') }}</a>
                <a href="{{ route('policies.privacy') }}">{{ __('Privacy policy') }}</a>
            </nav>
        </div>

        <div>
            <h2>{{ __('Contact us') }}</h2>
            @if(!empty($siteSettings['store_location'] ?? 'Dhaka, Bangladesh'))
                <p class="page-footer-detail"><span>{{ __('Address') }}</span>{{ $siteSettings['store_location'] ?? 'Dhaka, Bangladesh' }}</p>
            @endif
            @if(!empty($siteSettings['contact_phone']))
                <a class="page-footer-contact" href="tel:{{ preg_replace('/[^+0-9]/', '', $siteSettings['contact_phone']) }}">
                    <span>{{ __('Phone') }}</span>{{ $siteSettings['contact_phone'] }}
                </a>
            @endif
            @if(!empty($siteSettings['contact_email'] ?? 'hello@krishokerjhuri.com'))
                <a class="page-footer-contact" href="mailto:{{ $siteSettings['contact_email'] ?? 'hello@krishokerjhuri.com' }}">
                    <span>{{ __('Email') }}</span>{{ $siteSettings['contact_email'] ?? 'hello@krishokerjhuri.com' }}
                </a>
            @endif
            <a class="page-footer-text-link" href="{{ route('contact') }}">{{ __('Send us a message') }} <span aria-hidden="true">→</span></a>
        </div>

        <div>
            <h2>{{ __('My account') }}</h2>
            <nav class="page-footer-links" aria-label="{{ __('Account links') }}">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">{{ __('Admin dashboard') }}</a>
                    @else
                        <a href="{{ route('account.orders') }}">{{ __('Order history') }}</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">{{ __('Sign in') }}</a>
                    <a href="{{ route('register') }}">{{ __('Create an account') }}</a>
                @endauth
                <a href="{{ route('cart') }}">{{ __('Shopping cart') }}</a>
                <a href="{{ route('shop') }}">{{ __('Shop') }}</a>
            </nav>
        </div>

        <div>
            <h2>{{ __('Grow with us') }}</h2>
            <nav class="page-footer-links">
                <a href="{{ route('farmers') }}">{{ __('Meet our farmers') }}</a>
                <a href="{{ route('contact') }}">{{ __('Partner with us') }}</a>
            </nav>
            <div class="page-footer-app">
                <h2>{{ __('Mobile app') }}</h2>
                <button
                    class="footer-install-button"
                    type="button"
                    data-install-app
                    data-install-manual="{{ __('To install, open your browser menu and choose “Install app” or “Add to home screen”.') }}"
                    data-install-accepted="{{ __('The app is being installed on your device.') }}"
                    data-install-dismissed="{{ __('Installation was cancelled. You can try again from your browser menu.') }}"
                    data-install-failed="{{ __('The app could not be installed. Please use your browser menu to try again.') }}"
                    data-install-complete="{{ __('The app is installed on your device.') }}"
                >{{ __('Install our app') }}</button>
                <p class="footer-install-status" data-install-status aria-live="polite">{{ __('Add our store to your device for quick access.') }}</p>
            </div>
        </div>
    </div>

    <div class="container page-footer-bottom">
        <span>&copy; {{ now()->year }} {{ __($siteSettings['brand_name'] ?? 'Krishoker Jhuri') }}. {{ __('All rights reserved.') }}</span>
        <a href="{{ route('policies.privacy') }}">{{ __('Privacy') }}</a>
    </div>
</footer>
