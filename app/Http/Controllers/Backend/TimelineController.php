<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProjectTimeline;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TimelineController extends Controller
{
    /**
     * Display a listing of all timeline activities from all projects.
     */
    public function allActivities(Request $request): View
    {
        $query = ProjectTimeline::with('project', 'boqItem');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $timelines = $query->orderBy('planned_start_date', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_activities' => ProjectTimeline::count(),
            'completed' => ProjectTimeline::where('status', 'completed')->count(),
            'in_progress' => ProjectTimeline::where('status', 'in_progress')->count(),
            'delayed' => ProjectTimeline::where('status', 'delayed')->count(),
            'pending' => ProjectTimeline::where('status', 'pending')->count(),
        ];

        return view('backend.timeline.all', compact('timelines', 'stats'));
    }

    /**
     * Display a listing of timeline activities for a project.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->timelines()->with('boqItem');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $timelines = $query->orderBy('planned_start_date')->get();

        // Calculate statistics
        $stats = [
            'total_activities' => $timelines->count(),
            'completed' => $timelines->where('status', 'completed')->count(),
            'in_progress' => $timelines->where('status', 'in_progress')->count(),
            'delayed' => $timelines->where('status', 'delayed')->count(),
            'pending' => $timelines->where('status', 'pending')->count(),
        ];

        return view('backend.timeline.index', compact('project', 'timelines', 'stats'));
    }

    /**
     * Show the form for creating a new timeline activity.
     */
    public function create(Project $project): View
    {
        $boqItems = $project->boqItems()->orderBy('item_code')->get();
        return view('backend.timeline.create', compact('project', 'boqItems'));
    }

    /**
     * Store a newly created timeline activity in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity_name' => 'required|string|max:255',
            'activity_name_ar' => 'required|string|max:255',
            'boq_item_id' => 'nullable|exists:boq_items,id',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'required|date|after:planned_start_date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
        ]);

        // Calculate duration
        $startDate = Carbon::parse($validated['planned_start_date']);
        $endDate = Carbon::parse($validated['planned_end_date']);
        $validated['duration_days'] = $startDate->diffInDays($endDate);

        ProjectTimeline::create($validated);

        return redirect()->route('backend.timeline.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified timeline activity.
     */
    public function edit(ProjectTimeline $timeline): View
    {
        $project = $timeline->project;
        $boqItems = $project->boqItems()->orderBy('item_code')->get();

        // Calculate actual duration if dates are available
        if ($timeline->actual_start_date && $timeline->actual_end_date) {
            $timeline->actual_duration = Carbon::parse($timeline->actual_start_date)
                ->diffInDays(Carbon::parse($timeline->actual_end_date));
        }

        // Check if delayed
        $timeline->is_delayed = false;
        if ($timeline->actual_end_date) {
            $timeline->is_delayed = Carbon::parse($timeline->actual_end_date)
                ->greaterThan(Carbon::parse($timeline->planned_end_date));
        }

        return view('backend.timeline.edit', compact('timeline', 'project', 'boqItems'));
    }

    /**
     * Update the specified timeline activity in storage.
     */
    public function update(Request $request, ProjectTimeline $timeline): RedirectResponse
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_name_ar' => 'required|string|max:255',
            'boq_item_id' => 'nullable|exists:boq_items,id',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'required|date|after:planned_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
        ]);

        // Calculate planned duration
        $startDate = Carbon::parse($validated['planned_start_date']);
        $endDate = Carbon::parse($validated['planned_end_date']);
        $validated['duration_days'] = $startDate->diffInDays($endDate);

        // Check if delayed based on actual dates
        if (isset($validated['actual_end_date']) && $validated['actual_end_date']) {
            $actualEnd = Carbon::parse($validated['actual_end_date']);
            $plannedEnd = Carbon::parse($validated['planned_end_date']);

            if ($actualEnd->greaterThan($plannedEnd) && $validated['status'] !== 'completed') {
                $validated['status'] = 'delayed';
            }
        }

        // If status is completed and linked to BOQ item, update BOQ executed quantity
        if ($validated['status'] === 'completed' && $timeline->boq_item_id && $validated['boq_item_id']) {
            $boqItem = $timeline->boqItem;
            // This is a simple update - in real scenarios, you might want more complex logic
            // For now, we'll just ensure the BOQ item exists
        }

        $timeline->update($validated);

        return redirect()->route('backend.timeline.index', $timeline->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Update progress of a timeline activity (AJAX endpoint).
     */
    public function updateProgress(Request $request, ProjectTimeline $timeline): JsonResponse
    {
        $validated = $request->validate([
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
        ]);

        $timeline->update($validated);

        // If completed and linked to BOQ, you could update BOQ executed_quantity here
        // This would require additional logic based on your business rules

        return response()->json([
            'success' => true,
            'message' => __('messages.updated_successfully'),
            'timeline' => $timeline,
        ]);
    }

    /**
     * Remove the specified timeline activity from storage.
     */
    public function destroy(ProjectTimeline $timeline): RedirectResponse
    {
        $projectId = $timeline->project_id;
        $timeline->delete();

        return redirect()->route('backend.timeline.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
