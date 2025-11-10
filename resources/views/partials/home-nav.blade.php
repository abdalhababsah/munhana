<nav class="fixed inset-x-0 top-0 z-40 bg-white backdrop-blur border-b border-slate-100">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('dash/assets/images/logo-sm.png') }}" class="h-10" alt="logo">
                <img src="{{ asset('dash/assets/images/logo-light.png') }}" class="h-6 hidden md:block" alt="Munhana">
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="{{ route('home') }}#services" class="hover:text-primary">{{ __('messages.services') }}</a>
                <a href="{{ route('home') }}#projects" class="hover:text-primary">{{ __('messages.projects') }}</a>
                <a href="{{ route('contact.show') }}" class="hover:text-primary">{{ __('messages.contact_us') }}</a>
                <form method="POST" action="{{ route('language.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}">
                    @csrf
                    <button type="submit" class="hover:text-primary">{{ app()->getLocale() === 'en' ? __('messages.arabic') : __('messages.english') }}</button>
                </form>
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-[#d3af38] text-white shadow hover:bg-primary/90">{{ __('messages.login') }}</a>
            </div>
            <button id="mobile-menu-button" class="md:hidden text-slate-700">
                <i class="uil uil-bars text-2xl"></i>
            </button>
        </div>
        <div id="mobile-menu" class="md:hidden hidden flex flex-col gap-3 py-4 border-t border-slate-100 text-sm font-semibold text-slate-600">
            <a href="{{ route('home') }}#services" class="hover:text-primary">{{ __('messages.services') }}</a>
            <a href="{{ route('home') }}#projects" class="hover:text-primary">{{ __('messages.projects') }}</a>
            <a href="{{ route('contact.show') }}" class="hover:text-primary">{{ __('messages.contact_us') }}</a>
            <form method="POST" action="{{ route('language.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}">
                @csrf
                <button type="submit" class="hover:text-primary text-left">{{ app()->getLocale() === 'en' ? __('messages.arabic') : __('messages.english') }}</button>
            </form>
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-primary text-center text-white">{{ __('messages.login') }}</a>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        button?.addEventListener('click', () => menu.classList.toggle('hidden'));
    });
</script>
