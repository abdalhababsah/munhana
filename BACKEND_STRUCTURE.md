# Backend Dashboard Structure

This document explains the clean structure of the backend admin dashboard.

## Directory Structure

```
resources/views/backend/
├── layouts/
│   └── master.blade.php          # Main layout template
├── partials/
│   ├── header.blade.php          # Top navigation bar
│   ├── sidebar.blade.php         # Left sidebar menu
│   ├── footer.blade.php          # Footer component
│   ├── profile-menu.blade.php    # Right profile menu
│   └── search-modal.blade.php    # Search modal overlay
└── pages/
    └── dashboard.blade.php       # Dashboard page content
```

## Components Overview

### 1. Master Layout ([resources/views/backend/layouts/master.blade.php](resources/views/backend/layouts/master.blade.php))
The main layout file that includes:
- All CSS and JavaScript dependencies
- Common HTML structure
- Includes all partials (header, sidebar, footer)
- Provides content sections with `@yield` and `@stack` directives

### 2. Header ([resources/views/backend/partials/header.blade.php](resources/views/backend/partials/header.blade.php))
Contains:
- Logo
- Menu toggle button
- Page title (dynamic via `@yield('page-title')`)
- Profile menu toggle button

### 3. Sidebar ([resources/views/backend/partials/sidebar.blade.php](resources/views/backend/partials/sidebar.blade.php))
Contains:
- Brand logo
- Full navigation menu with collapsible submenus
- Active state detection using `request()->routeIs()`
- Laravel route integration

### 4. Footer ([resources/views/backend/partials/footer.blade.php](resources/views/backend/partials/footer.blade.php))
Contains:
- Copyright information
- Credits

### 5. Profile Menu ([resources/views/backend/partials/profile-menu.blade.php](resources/views/backend/partials/profile-menu.blade.php))
Right sidebar panel with:
- User profile information
- Quick action buttons (search, fullscreen)

### 6. Search Modal ([resources/views/backend/partials/search-modal.blade.php](resources/views/backend/partials/search-modal.blade.php))
Overlay modal for search functionality

## Assets

All dashboard assets are located in:
```
public/dash/assets/
├── css/           # Stylesheets
├── fonts/         # Font files
├── images/        # Images and icons
├── js/            # JavaScript files
└── libs/          # Third-party libraries
```

## Creating New Pages

To create a new backend page:

1. Create a new Blade file in `resources/views/backend/pages/`
2. Extend the master layout
3. Define your sections

Example:

```blade
@extends('backend.layouts.master')

@section('title', 'Your Page Title')
@section('page-title', 'Your Page Title')

@section('content')
    <!-- Your page content here -->
@endsection

@push('styles')
    <!-- Additional CSS for this page -->
@endpush

@push('scripts')
    <!-- Additional JavaScript for this page -->
@endpush
```

## Routes

Backend routes are organized under the `/backend` prefix with authentication middleware:

```php
Route::group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
```

## Controller

Backend controllers are organized in `app/Http/Controllers/Backend/`:
- [app/Http/Controllers/Backend/DashboardController.php](app/Http/Controllers/Backend/DashboardController.php)

## Accessing the Backend

- **URL:** `http://your-domain.com/backend/dashboard`
- **Route Name:** `backend.dashboard`
- **Middleware:** Requires authentication and email verification

## Customization Tips

### Changing Colors/Theme
Edit: [public/dash/assets/css/theme.min.css](public/dash/assets/css/theme.min.css)

### Adding New Menu Items
Edit: [resources/views/backend/partials/sidebar.blade.php](resources/views/backend/partials/sidebar.blade.php)

### Modifying Header
Edit: [resources/views/backend/partials/header.blade.php](resources/views/backend/partials/header.blade.php)

### Adding Page-Specific Assets
Use `@push('styles')` and `@push('scripts')` in your page templates

## Features

- Clean, modular component structure
- Responsive design
- Laravel route integration
- Active menu state detection
- Authentication integration
- Reusable layout system
- Easy to extend and customize
