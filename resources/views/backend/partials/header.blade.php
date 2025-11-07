<!-- Topbar Start -->
<header class="app-header flex items-center px-5 gap-4">

    <!-- Brand Logo -->
    <a href="{{ route('backend.dashboard') }}" class="logo-box">
        <img src="{{ asset('dash/assets/images/logo-sm.png') }}" class="h-6" alt="Small logo">
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <button id="button-toggle-menu" class="nav-link p-2">
        <span class="sr-only">Menu Toggle Button</span>
        <span class="flex items-center justify-center h-6 w-6">
            <i class="uil uil-bars text-2xl"></i>
        </span>
    </button>

    <!-- Page Title -->
    <h4 class="text-slate-900 text-lg font-medium">@yield('page-title', 'Dashboard')</h4>

    <button id="button-toggle-profile" class="nav-link p-2 ms-auto">
        <span class="sr-only">Profile Menu Offcanvas Button</span>
        <span class="flex items-center justify-center h-6 w-6">
            <i class="uil uil-heart-rate text-2xl"></i>
        </span>
    </button>
</header>
<!-- Topbar End -->
