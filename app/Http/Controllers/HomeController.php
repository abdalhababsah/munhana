<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application home page or redirect authenticated users to their dashboard.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Show home page for unauthenticated users
        return view('home');
    }
}
