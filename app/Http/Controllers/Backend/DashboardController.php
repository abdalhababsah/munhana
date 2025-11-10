<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the backend dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'active')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalClients = User::where('role', 'client')->count();

        // Get recent projects with client relationship
        $recentProjects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();

        // Get project completion statistics (by status)
        $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $recentContacts = Contact::latest()->take(5)->get();

        return view('backend.pages.dashboard', compact(
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'totalClients',
            'recentProjects',
            'projectsByStatus',
            'recentContacts'
        ));
    }
}
