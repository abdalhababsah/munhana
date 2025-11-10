<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Project;
use App\Models\SitePhoto;
use Illuminate\View\View;

class WorkerDashboardController extends Controller
{
    public function index(): View
    {
        $worker = auth()->user();

        // Get only projects assigned to this worker
        $assignedProjects = $worker->assignedProjects()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active/in-progress projects assigned to this worker
        $activeProjects = $worker->assignedProjects()
            ->whereIn('projects.status', ['active', 'in_progress'])
            ->with('client')
            ->orderBy('name')
            ->get();

        // Get recent reports for assigned projects only
        $assignedProjectIds = $assignedProjects->pluck('id');
        $recentReports = DailyReport::with('project')
            ->where('created_by', $worker->id)
            ->whereIn('project_id', $assignedProjectIds)
            ->latest('report_date')
            ->limit(5)
            ->get();

        // Calculate statistics for assigned projects only
        $reportsCount = DailyReport::where('created_by', $worker->id)
            ->whereIn('project_id', $assignedProjectIds)
            ->count();

        $photosCount = SitePhoto::where('uploaded_by', $worker->id)
            ->whereIn('project_id', $assignedProjectIds)
            ->count();

        return view('worker.pages.dashboard', [
            'worker' => $worker,
            'projects' => $activeProjects,
            'assignedProjects' => $assignedProjects,
            'recentReports' => $recentReports,
            'stats' => [
                'total_projects' => $assignedProjects->count(),
                'active_projects' => $activeProjects->count(),
                'completed_projects' => $assignedProjects->where('status', 'completed')->count(),
                'reports' => $reportsCount,
                'photos' => $photosCount,
            ],
        ]);
    }
}
