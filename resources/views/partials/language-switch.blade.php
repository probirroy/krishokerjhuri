<form action="{{ route('language.update', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" method="POST" class="language-switch">
    @csrf
    <button type="submit" aria-label="{{ __('Language') }}" title="{{ __('Language') }}">
        {{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}
    </button>
</form>
