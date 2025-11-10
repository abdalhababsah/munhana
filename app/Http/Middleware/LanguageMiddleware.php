<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'ar'; // Default locale

        // Check if user is authenticated
        if ($request->user()) {
            // Use user's language preference from database
            $locale = $request->user()->language ?? 'ar';
        } else {
            // For guests, check session or use default
            $locale = Session::get('locale', 'ar');
        }

        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // Set application locale
        App::setLocale($locale);

        // Store in session
        Session::put('locale', $locale);

        return $next($request);
    }
}
