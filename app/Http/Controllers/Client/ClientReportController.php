<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\MaterialDelivery;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\WeeklyPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientReportController extends Controller
{
    /**
     * Display all daily reports for a specific project (read-only).
     */
    public function dailyReports(Request $request, int $projectId): View|RedirectResponse
    {
        $project = Project::with('client')->findOrFail($projectId);

        if ($redirect = $this->authorizeProject($project)) {
            return $redirect;
        }

        $reportsQuery = $project->dailyReports()
            ->with('creator')
            ->orderBy('report_date', 'desc');

        if ($request->filled('start_date')) {
            $reportsQuery->whereDate('report_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $reportsQuery->whereDate('report_date', '<=', $request->end_date);
        }

        $dailyReports = $reportsQuery->get();

        $stats = [
            'total_reports' => $dailyReports->count(),
            'avg_workers' => (int) round($dailyReports->avg('worker_count') ?? 0),
            'total_hours' => round($dailyReports->sum('work_hours') ?? 0, 1),
            'avg_progress' => round($dailyReports->avg('completion_percentage') ?? 0, 1),
        ];

        return view('client.reports.daily-reports', [
            'project' => $project,
            'dailyReports' => $dailyReports,
            'filters' => $request->only(['start_date', 'end_date']),
            'stats' => $stats,
        ]);
    }

    /**
     * Display a single daily report with comments.
     */
    public function showDailyReport(int $reportId): View|RedirectResponse
    {
        $report = DailyReport::with([
            'project',
            'creator',
            'comments.user',
        ])->findOrFail($reportId);

        if ($redirect = $this->authorizeProject($report->project)) {
            return $redirect;
        }

        $project = $report->project;

        return view('client.reports.daily-report-show', [
            'project' => $project,
            'report' => $report,
            'comments' => $report->comments,
        ]);
    }

    /**
     * Display read-only material deliveries for a project.
     */
    public function materialDeliveries(int $projectId): View|RedirectResponse
    {
        $project = Project::findOrFail($projectId);

        if ($redirect = $this->authorizeProject($project)) {
            return $redirect;
        }

        $deliveries = MaterialDelivery::with('boqItem')
            ->where('project_id', $project->id)
            ->orderBy('delivery_date', 'desc')
            ->get();

        $summary = [
            'total_deliveries' => $deliveries->count(),
            'total_quantity' => $deliveries->sum('quantity'),
            'unique_suppliers' => $deliveries->pluck('supplier_name')->filter()->unique()->count(),
        ];

        $groupedDeliveries = $deliveries->groupBy('boq_item_id');

        return view('client.reports.material-deliveries', [
            'project' => $project,
            'deliveries' => $deliveries,
            'groupedDeliveries' => $groupedDeliveries,
            'summary' => $summary,
        ]);
    }

    /**
     * Display read-only site visits for a project.
     */
    public function siteVisits(int $projectId): View|RedirectResponse
    {
        $project = Project::findOrFail($projectId);

        if ($redirect = $this->authorizeProject($project)) {
            return $redirect;
        }

        $siteVisits = SiteVisit::with('creator')
            ->where('project_id', $project->id)
            ->orderBy('visit_date', 'desc')
            ->get();

        return view('client.reports.site-visits', [
            'project' => $project,
            'siteVisits' => $siteVisits,
        ]);
    }

    /**
     * Display read-only weekly plans for a project.
     */
    public function weeklyPlans(int $projectId): View|RedirectResponse
    {
        $project = Project::findOrFail($projectId);

        if ($redirect = $this->authorizeProject($project)) {
            return $redirect;
        }

        $weeklyPlans = WeeklyPlan::with('creator')
            ->where('project_id', $project->id)
            ->orderBy('week_start_date', 'desc')
            ->get();

        return view('client.reports.weekly-plans', [
            'project' => $project,
            'weeklyPlans' => $weeklyPlans,
        ]);
    }

    /**
     * Ensure the authenticated client owns the project.
     */
    protected function authorizeProject(Project $project): ?RedirectResponse
    {
        if ($project->client_id !== auth()->id()) {
            return redirect()
                ->route('client.projects.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        return null;
    }
}
