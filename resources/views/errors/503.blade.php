<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">

<head>
    <meta charset="utf-8">
    <title>{{ __('messages.under_maintenance') }} | {{ __('messages.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ __('messages.app_name') }}" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('dist/assets/images/favicon.ico') }}">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class=" h-screen w-screen flex justify-center items-center">
    <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full px-4">
        <div class="card overflow-hidden sm:rounded-md rounded-none">
            <div class="px-6 py-8">
                <a href="{{ route('home') }}" class="flex justify-center mb-8">
                    <span class="text-2xl font-bold text-primary">{{ __('messages.app_name') }}</span>
                </a>

                <div class="w-1/2 mx-auto block my-8">
                    <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
                        <!-- Maintenance Illustration -->
                        <!-- Gear 1 -->
                        <g transform="translate(150, 200)">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#3B82F6" stroke-width="8"/>
                            <circle cx="50" cy="50" r="20" fill="#3B82F6"/>
                            <rect x="46" y="10" width="8" height="20" rx="4" fill="#3B82F6"/>
                            <rect x="46" y="70" width="8" height="20" rx="4" fill="#3B82F6"/>
                            <rect x="10" y="46" width="20" height="8" rx="4" fill="#3B82F6"/>
                            <rect x="70" y="46" width="20" height="8" rx="4" fill="#3B82F6"/>
                        </g>

                        <!-- Gear 2 -->
                        <g transform="translate(250, 200)">
                            <circle cx="50" cy="50" r="35" fill="none" stroke="#10B981" stroke-width="7"/>
                            <circle cx="50" cy="50" r="18" fill="#10B981"/>
                            <rect x="47" y="15" width="6" height="16" rx="3" fill="#10B981"/>
                            <rect x="47" y="69" width="6" height="16" rx="3" fill="#10B981"/>
                            <rect x="15" y="47" width="16" height="6" rx="3" fill="#10B981"/>
                            <rect x="69" y="47" width="16" height="6" rx="3" fill="#10B981"/>
                        </g>

                        <!-- Wrench -->
                        <g transform="translate(200, 150) rotate(-45 50 50)">
                            <rect x="40" y="20" width="20" height="100" rx="4" fill="#6B7280"/>
                            <circle cx="50" cy="30" r="15" fill="none" stroke="#6B7280" stroke-width="4"/>
                        </g>
                    </svg>
                </div>

                <h3 class="text-dark mb-4 mt-6 text-center text-2xl font-bold">
                    {{ __('messages.under_maintenance') }}
                </h3>

                <p class="text-dark/75 mb-8 w-3/4 mx-auto text-base text-center">
                    {{ __('messages.under_maintenance_description') }}
                </p>

                <div class="flex justify-center">
                    <a href="mailto:{{ config('mail.from.address', 'support@munhana.com') }}"
                       class="btn text-white bg-success inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('messages.contact_us') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
