<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WorkerAssignmentController extends Controller
{
    /**
     * Display worker assignments for a project.
     */
    public function index(Project $project): View
    {
        $project->load(['assignedUsers' => function ($query) {
            $query->where('role', 'worker');
        }]);

        // Get all workers not assigned to this project
        $assignedWorkerIds = $project->assignedUsers->pluck('id');
        $availableWorkers = User::where('role', 'worker')
            ->whereNotIn('id', $assignedWorkerIds)
            ->orderBy('name')
            ->get();

        return view('backend.workers.assignments', compact('project', 'availableWorkers'));
    }

    /**
     * Assign a worker to a project.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'role_description' => 'nullable|string|max:500',
            'role_description_ar' => 'nullable|string|max:500',
        ]);

        // Verify the user is a worker
        $worker = User::findOrFail($validated['worker_id']);
        if ($worker->role !== 'worker') {
            return redirect()->back()->with('error', __('messages.user_must_be_worker'));
        }

        // Check if already assigned
        if ($project->assignedUsers()->where('user_id', $worker->id)->exists()) {
            return redirect()->back()->with('error', __('messages.worker_already_assigned'));
        }

        // Assign worker to project
        $project->assignedUsers()->attach($worker->id, [
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'role_description' => $validated['role_description'] ?? null,
            'role_description_ar' => $validated['role_description_ar'] ?? null,
        ]);

        return redirect()->route('backend.workers.assignments', $project)
            ->with('success', __('messages.worker_assigned_successfully'));
    }

    /**
     * Update worker assignment details.
     */
    public function update(Request $request, Project $project, User $worker): RedirectResponse
    {
        $validated = $request->validate([
            'role_description' => 'nullable|string|max:500',
            'role_description_ar' => 'nullable|string|max:500',
        ]);

        // Update the pivot data
        $project->assignedUsers()->updateExistingPivot($worker->id, [
            'role_description' => $validated['role_description'] ?? null,
            'role_description_ar' => $validated['role_description_ar'] ?? null,
        ]);

        return redirect()->route('backend.workers.assignments', $project)
            ->with('success', __('messages.assignment_updated_successfully'));
    }

    /**
     * Remove a worker from a project.
     */
    public function destroy(Project $project, User $worker): RedirectResponse
    {
        $project->assignedUsers()->detach($worker->id);

        return redirect()->route('backend.workers.assignments', $project)
            ->with('success', __('messages.worker_removed_successfully'));
    }

    /**
     * Display all workers and their project assignments.
     */
    public function allWorkers(): View
    {
        $workers = User::where('role', 'worker')
            ->withCount('assignedProjects')
            ->with(['assignedProjects' => function ($query) {
                $query->select('projects.id', 'projects.name', 'projects.name_ar', 'projects.status')
                    ->orderBy('projects.created_at', 'desc');
            }])
            ->orderBy('name')
            ->get();

        return view('backend.workers.index', compact('workers'));
    }

    /**
     * Display a worker's profile with all assignments.
     */
    public function show(User $worker): View
    {
        if ($worker->role !== 'worker') {
            abort(404);
        }

        $worker->load(['assignedProjects' => function ($query) {
            $query->with('client')->orderBy('created_at', 'desc');
        }]);

        // Calculate statistics
        $stats = [
            'total_projects' => $worker->assignedProjects->count(),
            'active_projects' => $worker->assignedProjects->whereIn('status', ['active', 'in_progress'])->count(),
            'completed_projects' => $worker->assignedProjects->where('status', 'completed')->count(),
            'total_reports' => $worker->dailyReports()->count(),
        ];

        return view('backend.workers.show', compact('worker', 'stats'));
    }
}
