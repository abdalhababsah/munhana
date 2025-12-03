<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Munhana" name="author">

    <meta name="robots" content="noindex, nofollow">

    {{-- عنوان الصفحة (تم تحديث الترجمة لتعكس "منحنى") --}}
    <title>@yield('title', 'لوحة التحكم') | {{ config('app.name', 'منحنى') }}</title>

    {{-- البيانات الوصفية للمشاركة (واتساب، لينكد إن، إلخ.) --}}
    <meta name="description" content="لوحة التحكم الإدارية لتطبيق تتبع مشاريع البنية التحتية لمنحنى.">
    
    {{-- Open Graph / فيسبوك / واتساب --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'لوحة التحكم') | {{ config('app.name') }}">
    <meta property="og:description" content="إدارة مشاريع البنية التحتية وتتبعها وتحديثات الإنشاءات لمنحنى.">
    {{-- هذا يضمن ظهور الشعار الصحيح عند المشاركة --}}
    <meta property="og:image" content="{{ asset('dash/assets/images/logo-light.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- بطاقة تويتر (Twitter Card) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'لوحة التحكم') | {{ config('app.name') }}">
    <meta name="twitter:description" content="إدارة مشاريع البنية التحتية وتتبعها وتحديثات الإنشاءات لمنحنى.">
    <meta name="twitter:image" content="{{ asset('dash/assets/images/logo-light.png') }}">

    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">

    <link href="{{ asset('dash/assets/css/theme.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">

    @stack('styles')

    <script src="{{ asset('dash/assets/js/head.js') }}"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <div class="app-wrapper">

        @include('backend.partials.sidebar')

        <div class="app-content">

            @include('backend.partials.header')

            <main class="p-6">
                @include('backend.partials.alerts')
                @yield('content')
            </main>

            @include('backend.partials.footer')

        </div>
        @include('backend.partials.profile-menu')

        @include('backend.partials.search-modal')

    </div>

    <script src="{{ asset('dash/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/preline/preline.js') }}"></script>

    <script src="{{ asset('dash/assets/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>