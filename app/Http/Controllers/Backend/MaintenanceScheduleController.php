<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display completed projects for maintenance navigation.
     */
    public function projectSelection(): View
    {
        $projects = Project::where('status', 'completed')->orderBy('name')->get();

        return view('backend.maintenance.schedules.projects', compact('projects'));
    }

    /**
     * Display maintenance schedules for the given project.
     */
    public function index(Project $project, Request $request): View
    {
        $status = $request->get('status');

        $query = $project->maintenanceSchedules()->orderBy('maintenance_date');
        if ($status) {
            $query->where('status', $status);
        }

        $schedules = $query->get();

        $upcoming = $project->maintenanceSchedules()
            ->where('status', 'scheduled')
            ->whereDate('maintenance_date', '>=', now()->toDateString())
            ->orderBy('maintenance_date')
            ->first();

        return view('backend.maintenance.schedules.index', compact('project', 'schedules', 'status', 'upcoming'));
    }

    /**
     * Show the form for creating a new maintenance schedule.
     */
    public function create(Project $project): View
    {
        return view('backend.maintenance.schedules.create', compact('project'));
    }

    /**
     * Store a newly created maintenance schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|string|max:255',
            'maintenance_type_ar' => 'required|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        MaintenanceSchedule::create($data);

        return redirect()
            ->route('backend.maintenance-schedules.index', $data['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Update the specified maintenance schedule.
     */
    public function update(Request $request, MaintenanceSchedule $maintenanceSchedule): RedirectResponse
    {
        $data = $request->validate([
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|string|max:255',
            'maintenance_type_ar' => 'required|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        $maintenanceSchedule->update($data);

        return redirect()
            ->route('backend.maintenance-schedules.index', $maintenanceSchedule->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified maintenance schedule.
     */
    public function destroy(MaintenanceSchedule $maintenanceSchedule): RedirectResponse
    {
        $projectId = $maintenanceSchedule->project_id;
        $maintenanceSchedule->delete();

        return redirect()
            ->route('backend.maintenance-schedules.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
