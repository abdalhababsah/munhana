<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SiteVisitController extends Controller
{
    /**
     * Display a listing of site visits for a project.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->siteVisits()->with('creator');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('visit_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('visit_date', '<=', $request->date_to);
        }

        $visits = $query->orderBy('visit_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_visits' => $visits->count(),
        ];

        return view('backend.reports.visits.index', compact('project', 'visits', 'stats'));
    }

    /**
     * Show the form for creating a new site visit.
     */
    public function create(Project $project): View
    {
        return view('backend.reports.visits.create', compact('project'));
    }

    /**
     * Store a newly created site visit in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'visit_date' => 'required|date',
            'visitor_name' => 'required|string|max:255',
            'purpose' => 'required|string',
            'purpose_ar' => 'nullable|string',
            'findings' => 'required|string',
            'findings_ar' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        SiteVisit::create($validated);

        return redirect()->route('backend.visits.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified site visit.
     */
    public function show(SiteVisit $visit): View
    {
        $visit->load('project', 'creator');

        return view('backend.reports.visits.show', compact('visit'));
    }

    /**
     * Show the form for editing the specified site visit.
     */
    public function edit(SiteVisit $visit): View
    {
        $project = $visit->project;

        return view('backend.reports.visits.edit', compact('visit', 'project'));
    }

    /**
     * Update the specified site visit in storage.
     */
    public function update(Request $request, SiteVisit $visit): RedirectResponse
    {
        $validated = $request->validate([
            'visit_date' => 'required|date',
            'visitor_name' => 'required|string|max:255',
            'purpose' => 'required|string',
            'purpose_ar' => 'nullable|string',
            'findings' => 'required|string',
            'findings_ar' => 'nullable|string',
        ]);

        $visit->update($validated);

        return redirect()->route('backend.visits.index', $visit->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified site visit from storage.
     */
    public function destroy(SiteVisit $visit): RedirectResponse
    {
        $projectId = $visit->project_id;
        $visit->delete();

        return redirect()->route('backend.visits.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
