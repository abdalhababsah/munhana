<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 1. Primary Meta Tags --}}
    <title>@yield('title', config('app.name', 'Munhana')) | {{ __('messages.app_tagline') }}</title>
    <meta name="description" content="@yield('meta_description', __('messages.meta_description', ['app_name' => config('app.name')]))">
    <meta name="keywords" content="infrastructure, construction tracker, project management, turnkey solutions, interior design, fitout, architecture, engineering, building app">
    <meta name="author" content="{{ config('app.name') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- 2. Open Graph / Facebook / WhatsApp (The Fix for Sharing) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name')) - {{ __('messages.app_tagline') }}">
    <meta property="og:description" content="@yield('meta_description', __('messages.meta_description'))">
    <meta property="og:image" content="{{ asset('dash/assets/images/logo-light.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_SA' : 'en_US' }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- 3. Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', __('messages.meta_description'))">
    <meta name="twitter:image" content="{{ asset('dash/assets/images/logo-light.png') }}">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">
    
    {{-- CSS & Scripts --}}
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen"
    style="background-color:#f7f4ec; color:#2f2f2f; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;"
>
    @include('partials.home-nav')

    <main class="pt-20">
        @yield('content')
    </main>

    <footer class=" pt-12 pb-8" style="background-color:#2f2f2f; color:#ffffff;">
        <div class="container mx-auto px-4 grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            {{-- Column 1: Brand & About --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('dash/assets/images/logo-light.png') }}" alt="{{ config('app.name') }} logo" class="h-10">
                </div>
                <p class="text-sm" style="color:rgba(255,255,255,0.8);">
                    {{ __('messages.footer_about') }}
                </p>
    
                <div class="flex items-center gap-3 pt-2">
                    {{-- Social Links --}}
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition"
                       style="background-color:rgba(230,218,192,0.07); color:#e6dac0;"
                       onmouseover="this.style.backgroundColor='#d3af38';this.style.color='#2f2f2f';"
                       onmouseout="this.style.backgroundColor='rgba(230,218,192,0.07)';this.style.color='#e6dac0';">
                        <i class="uil uil-instagram"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition"
                       style="background-color:rgba(230,218,192,0.07); color:#e6dac0;"
                       onmouseover="this.style.backgroundColor='#d3af38';this.style.color='#2f2f2f';"
                       onmouseout="this.style.backgroundColor='rgba(230,218,192,0.07)';this.style.color='#e6dac0';">
                        <i class="uil uil-facebook-f"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition"
                       style="background-color:rgba(230,218,192,0.07); color:#e6dac0;"
                       onmouseover="this.style.backgroundColor='#d3af38';this.style.color='#2f2f2f';"
                       onmouseout="this.style.backgroundColor='rgba(230,218,192,0.07)';this.style.color='#e6dac0';">
                        <i class="uil uil-linkedin-alt"></i>
                    </a>
                </div>
            </div>
    
            {{-- Column 2: Quick Links --}}
            <div>
                <h4 class="text-sm font-semibold mb-4 tracking-wide uppercase"
                    style="color:#e6dac0;">
                    {{ __('messages.quick_links') }}
                </h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.8);">
                    <li>
                        <a href="{{ route('home') }}"
                           class="transition-colors"
                           style="color:inherit;"
                           onmouseover="this.style.color='#d3af38';"
                           onmouseout="this.style.color='rgba(255,255,255,0.8)';">
                            {{ __('messages.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact.show') }}"
                           class="transition-colors"
                           style="color:inherit;"
                           onmouseover="this.style.color='#d3af38';"
                           onmouseout="this.style.color='rgba(255,255,255,0.8)';">
                            {{ __('messages.contact_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}"
                           class="transition-colors"
                           style="color:inherit;"
                           onmouseover="this.style.color='#d3af38';"
                           onmouseout="this.style.color='rgba(255,255,255,0.8)';">
                            {{ __('messages.login') }}
                        </a>
                    </li>
                    <li>
                        <a href="#services"
                           class="transition-colors"
                           style="color:inherit;"
                           onmouseover="this.style.color='#d3af38';"
                           onmouseout="this.style.color='rgba(255,255,255,0.8)';">
                            {{ __('messages.services_title') }}
                        </a>
                    </li>
                    <li>
                        <a href="#projects"
                           class="transition-colors"
                           style="color:inherit;"
                           onmouseover="this.style.color='#d3af38';"
                           onmouseout="this.style.color='rgba(255,255,255,0.8)';">
                            {{ __('messages.projects_title') }}
                        </a>
                    </li>
                </ul>
            </div>
    
            {{-- Column 3: Services Summary --}}
            <div>
                <h4 class="text-sm font-semibold mb-4 tracking-wide uppercase"
                    style="color:#e6dac0;">
                    {{ __('messages.services') ?? 'Services' }}
                </h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.8);">
                    <li>• {{ __('messages.service_arch_title') }}</li>
                    <li>• {{ __('messages.service_interior_title') }}</li>
                    <li>• {{ __('messages.service_fitout_title') }}</li>
                    <li>• {{ __('messages.service_turnkey_title') }}</li>
                </ul>
                <p class="mt-4 text-xs" style="color:rgba(255,255,255,0.7);">
                    {{ __('messages.process_subtitle') }}
                </p>
            </div>
    
            {{-- Column 4: Contact & Address --}}
            <div class="space-y-4 text-sm" style="color:rgba(255,255,255,0.85);">
                <h4 class="text-sm font-semibold mb-2 tracking-wide uppercase"
                    style="color:#e6dac0;">
                    {{ __('messages.contact') }}
                </h4>
    
                <div>
                    <p class="text-[11px] uppercase tracking-wide"
                       style="color:rgba(255,255,255,0.5);">
                        {{ __('messages.phone') }}
                    </p>
                    <p class="text-base font-semibold">
                        00962781975554
                    </p>
                </div>
    
                <div>
                    <p class="text-[11px] uppercase tracking-wide"
                       style="color:rgba(255,255,255,0.5);">
                        {{ __('messages.email') }}
                    </p>
                    <p class="text-base font-semibold">
                        contact@munhana.com
                    </p>
                </div>
    
                <div>
                    <p class="text-[11px] uppercase tracking-wide mb-1"
                       style="color:rgba(255,255,255,0.5);">
                        {{ __('messages.office_address') ?? __('messages.office_address_text') }}
                    </p>
                    <p>{{ __('messages.office_address_text') }}</p>
                </div>
    
                <div class="pt-2">
                    <p class="text-[11px] uppercase tracking-wide mb-1"
                       style="color:rgba(255,255,255,0.5);">
                        {{ __('messages.working_hours') ?? 'Working Hours' }}
                    </p>
                    <p>
                        {{ __('messages.working_hours_value') ?? 'Sun – Thu, 9:00 AM – 6:00 PM (KSA time)' }}
                    </p>
                </div>
            </div>
        </div>
    
        <div class="mt-8 pt-6 border-t"
             style="border-color:rgba(255,255,255,0.1);">
            <p class="text-center text-xs"
               style="color:rgba(255,255,255,0.6);">
                &copy; {{ date('Y') }} {{ config('app.name', 'Munhana') }} — {{ __('messages.all_rights_reserved') }}
            </p>
        </div>
    </footer>
</body>
</html>