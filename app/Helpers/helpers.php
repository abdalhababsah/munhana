<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

if (!function_exists('switchLanguage')) {
    /**
     * Switch application language
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    function switchLanguage(string $locale)
    {
        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // If user is authenticated, update their language preference in database
        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $locale;
            $user->save();
        }

        // Store in session
        Session::put('locale', $locale);

        // Set application locale
        app()->setLocale($locale);

        // Return back to previous page
        return redirect()->back()->with('success', __('messages.language_changed'));
    }
}

if (!function_exists('getCurrentLocale')) {
    /**
     * Get current application locale
     *
     * @return string
     */
    function getCurrentLocale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('isRtl')) {
    /**
     * Check if current locale is RTL
     *
     * @return bool
     */
    function isRtl(): bool
    {
        return app()->getLocale() === 'ar';
    }
}
