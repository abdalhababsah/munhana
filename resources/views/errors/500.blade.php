<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.server_error') }} | {{ __('messages.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-6">
    <div class="bg-white/95 rounded-3xl shadow-2xl max-w-lg w-full p-10 text-center space-y-6">
        <img src="{{ asset('dash/assets/images/logo-light.png') }}" class="h-10 mx-auto" alt="logo">
        <p class="text-sm uppercase tracking-[0.4em] text-slate-400">{{ __('messages.server_error') }}</p>
        <h1 class="text-6xl font-bold text-danger">500</h1>
        <p class="text-slate-600">{{ __('messages.server_error_description') }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary w-full">{{ __('messages.back_to_home') }}</a>
    </div>
</body>
</html>
