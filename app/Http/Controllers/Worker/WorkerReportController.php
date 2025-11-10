<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerReportController extends Controller
{
    public function index(): View
    {
        $worker = auth()->user();

        // Get only assigned projects
        $projects = $worker->assignedProjects()
            ->whereIn('projects.status', ['active', 'in_progress'])
            ->orderBy('name')
            ->get();

        return view('worker.projects.index', compact('projects'));
    }

    public function create(Project $project): View
    {
        $worker = auth()->user();

        // Verify worker is assigned to this project
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, __('messages.not_assigned_to_project'));
        }

        return view('worker.reports.create', compact('project'));
    }

    public function store(Request $request): RedirectResponse
    {
        $worker = auth()->user();

        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'worker_count' => 'required|integer|min:0',
            'work_hours' => 'required|numeric|min:0',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        // Verify worker is assigned to this project
        $project = Project::findOrFail($data['project_id']);
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('worker.dashboard')
                ->with('error', __('messages.not_assigned_to_project'));
        }

        $data['created_by'] = $worker->id;

        DailyReport::create($data);

        return redirect()->route('worker.dashboard')->with('success', __('messages.report_created_successfully'));
    }

    public function show(Project $project, DailyReport $report): View
    {
        $worker = auth()->user();

        // Verify worker is assigned to this project
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, __('messages.not_assigned_to_project'));
        }

        // Verify this report belongs to this worker
        if ($report->created_by !== $worker->id) {
            abort(403, __('messages.access_denied'));
        }

        // Verify this report belongs to this project
        if ($report->project_id !== $project->id) {
            abort(404);
        }

        return view('worker.reports.show', compact('project', 'report'));
    }
}
