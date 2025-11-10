<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientDashboardController extends Controller
{
    /**
     * Display the client dashboard.
     */
    public function index(): View
    {
        $client = auth()->user();

        // Get all projects for this client
        $projects = Project::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->whereIn('status', ['active', 'in_progress'])->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'avg_completion' => $projects->avg('completion_percentage') ?? 0,
        ];

        // Get recent projects (limit 5)
        $recentProjects = $projects->take(5);

        // Get recent daily reports from client's projects
        $projectIds = $projects->pluck('id');
        $recentReports = DailyReport::whereIn('project_id', $projectIds)
            ->with('project', 'creator')
            ->orderBy('report_date', 'desc')
            ->limit(10)
            ->get();

        return view('client.pages.dashboard', compact('client', 'stats', 'recentProjects', 'recentReports'));
    }
}
