<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class DailyReportController extends Controller
{
    /**
     * Display a listing of all daily reports from all projects.
     */
    public function allReports(Request $request): View
    {
        $query = DailyReport::with('project', 'creator');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('report_date', '<=', $request->date_to);
        }

        $reports = $query->orderBy('report_date', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_reports' => DailyReport::count(),
            'avg_workers' => DailyReport::avg('worker_count') ?? 0,
            'avg_work_hours' => DailyReport::avg('work_hours') ?? 0,
            'latest_completion' => DailyReport::orderBy('report_date', 'desc')->first()->completion_percentage ?? 0,
        ];

        return view('backend.reports.daily.all', compact('reports', 'stats'));
    }

    /**
     * Display a listing of daily reports for a project.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->dailyReports()->with('creator');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('report_date', '<=', $request->date_to);
        }

        $reports = $query->orderBy('report_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_reports' => $reports->count(),
            'avg_workers' => $reports->avg('worker_count') ?? 0,
            'avg_work_hours' => $reports->avg('work_hours') ?? 0,
            'current_completion' => $reports->first()->completion_percentage ?? 0,
        ];

        return view('backend.reports.daily.index', compact('project', 'reports', 'stats'));
    }

    /**
     * Show the form for creating a new daily report.
     */
    public function create(Project $project): View
    {
        $today = Carbon::today()->format('Y-m-d');

        // Check if report exists for today
        $existsToday = $project->dailyReports()
            ->whereDate('report_date', $today)
            ->exists();

        return view('backend.reports.daily.create', compact('project', 'today', 'existsToday'));
    }

    /**
     * Store a newly created daily report in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'worker_count' => 'required|integer|min:0',
            'work_hours' => 'required|numeric|min:0',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        DailyReport::create($validated);

        return redirect()->route('backend.reports.daily.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified daily report.
     */
    public function show(DailyReport $report): View
    {
        $report->load('project', 'creator', 'comments.user');

        return view('backend.reports.daily.show', compact('report'));
    }

    /**
     * Show the form for editing the specified daily report.
     */
    public function edit(DailyReport $report): View
    {
        $project = $report->project;

        return view('backend.reports.daily.edit', compact('report', 'project'));
    }

    /**
     * Update the specified daily report in storage.
     */
    public function update(Request $request, DailyReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'worker_count' => 'required|integer|min:0',
            'work_hours' => 'required|numeric|min:0',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        $report->update($validated);

        return redirect()->route('backend.reports.daily.index', $report->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified daily report from storage.
     */
    public function destroy(DailyReport $report): RedirectResponse
    {
        $projectId = $report->project_id;
        $report->delete();

        return redirect()->route('backend.reports.daily.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
