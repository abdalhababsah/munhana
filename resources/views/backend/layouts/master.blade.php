<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') | TailFox - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('dash/assets/css/theme.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">

    <!-- Additional CSS -->
    @stack('styles')

    <!-- Head Js -->
    <script src="{{ asset('dash/assets/js/head.js') }}"></script>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <div class="app-wrapper">

        @include('backend.partials.sidebar')

        <!-- Start Page Content here -->
        <div class="app-content">

            @include('backend.partials.header')

            <main class="p-6">
                @include('backend.partials.alerts')
                @yield('content')
            </main>

            @include('backend.partials.footer')

        </div>
        <!-- End Page content -->

        @include('backend.partials.profile-menu')

        @include('backend.partials.search-modal')

    </div>

    <!-- Plugin Js -->
    <script src="{{ asset('dash/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/preline/preline.js') }}"></script>

    <!-- App Js -->
    <script src="{{ asset('dash/assets/js/app.js') }}"></script>

    <!-- Additional Scripts -->
    @stack('scripts')

</body>

</html>
