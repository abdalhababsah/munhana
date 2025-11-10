<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\WeeklyPlan;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class WeeklyPlanController extends Controller
{
    /**
     * Display a listing of weekly plans for a project.
     */
    public function index(Project $project): View
    {
        $plans = $project->weeklyPlans()
            ->with('creator')
            ->orderBy('week_start_date', 'desc')
            ->get();

        // Get current week's plan
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $currentPlan = $plans->first(function ($plan) use ($currentWeekStart, $currentWeekEnd) {
            $planStart = Carbon::parse($plan->week_start_date);
            $planEnd = Carbon::parse($plan->week_end_date);
            return $planStart->lte($currentWeekEnd) && $planEnd->gte($currentWeekStart);
        });

        // Calculate statistics
        $stats = [
            'total_plans' => $plans->count(),
        ];

        return view('backend.reports.weekly-plans.index', compact('project', 'plans', 'currentPlan', 'stats'));
    }

    /**
     * Show the form for creating a new weekly plan.
     */
    public function create(Project $project): View
    {
        // Calculate current week start (Monday) and end (Sunday)
        $weekStart = Carbon::now()->startOfWeek()->format('Y-m-d');
        $weekEnd = Carbon::now()->endOfWeek()->format('Y-m-d');

        return view('backend.reports.weekly-plans.create', compact('project', 'weekStart', 'weekEnd'));
    }

    /**
     * Store a newly created weekly plan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after:week_start_date',
            'planned_activities' => 'required|string',
            'planned_activities_ar' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        WeeklyPlan::create($validated);

        return redirect()->route('backend.weekly-plans.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified weekly plan.
     */
    public function show(WeeklyPlan $weeklyPlan): View
    {
        $weeklyPlan->load('project', 'creator');

        return view('backend.reports.weekly-plans.show', compact('weeklyPlan'));
    }

    /**
     * Show the form for editing the specified weekly plan.
     */
    public function edit(WeeklyPlan $weeklyPlan): View
    {
        $project = $weeklyPlan->project;

        return view('backend.reports.weekly-plans.edit', compact('weeklyPlan', 'project'));
    }

    /**
     * Update the specified weekly plan in storage.
     */
    public function update(Request $request, WeeklyPlan $weeklyPlan): RedirectResponse
    {
        $validated = $request->validate([
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after:week_start_date',
            'planned_activities' => 'required|string',
            'planned_activities_ar' => 'nullable|string',
        ]);

        $weeklyPlan->update($validated);

        return redirect()->route('backend.weekly-plans.index', $weeklyPlan->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified weekly plan from storage.
     */
    public function destroy(WeeklyPlan $weeklyPlan): RedirectResponse
    {
        $projectId = $weeklyPlan->project_id;
        $weeklyPlan->delete();

        return redirect()->route('backend.weekly-plans.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
