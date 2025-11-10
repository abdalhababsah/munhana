<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class WorkerProjectController extends Controller
{
    /**
     * Display a listing of projects assigned to the worker.
     */
    public function index(): View
    {
        $worker = auth()->user();

        // Get only projects assigned to this worker
        $projects = $worker->assignedProjects()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('worker.projects.index', compact('projects'));
    }

    /**
     * Display the specified project (only if worker is assigned to it).
     */
    public function show(Project $project): View
    {
        $worker = auth()->user();

        // The middleware handles authorization, but we still verify
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, __('messages.not_assigned_to_project'));
        }

        // Load relationships
        $project->load([
            'client',
            'boqs',
            'timelines',
            'dailyReports' => function ($query) use ($worker) {
                $query->where('created_by', $worker->id)
                    ->latest('report_date')
                    ->limit(5);
            },
            'materialDeliveries' => function ($query) use ($worker) {
                $query->where('received_by', $worker->id)
                    ->latest('delivery_date')
                    ->limit(5);
            },
            'sitePhotos' => function ($query) use ($worker) {
                $query->where('uploaded_by', $worker->id)
                    ->latest('photo_date')
                    ->limit(10);
            },
        ]);

        // Calculate worker-specific statistics for this project
        $workerStats = [
            'total_reports' => $project->dailyReports()->where('created_by', $worker->id)->count(),
            'total_materials' => $project->materialDeliveries()->where('received_by', $worker->id)->count(),
            'total_photos' => $project->sitePhotos()->where('uploaded_by', $worker->id)->count(),
        ];

        // Get worker's role on this project
        $workerRole = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->first()
            ?->pivot;

        return view('worker.projects.show', compact('project', 'workerStats', 'workerRole'));
    }
}
