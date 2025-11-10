<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProjectCost;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectCostController extends Controller
{
    /**
     * Display a listing of project costs.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->projectCosts();

        // Filter by cost type
        if ($request->filled('cost_type')) {
            $query->where('cost_type', $request->cost_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('cost_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('cost_date', '<=', $request->date_to);
        }

        $costs = $query->orderBy('cost_date', 'desc')->get();

        // Group by cost type and calculate totals
        $groupedCosts = $costs->groupBy('cost_type')->map(function ($items) {
            return [
                'total' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        });

        // Calculate totals for each type
        $stats = [
            'labor' => $groupedCosts->get('labor')['total'] ?? 0,
            'material' => $groupedCosts->get('material')['total'] ?? 0,
            'equipment' => $groupedCosts->get('equipment')['total'] ?? 0,
            'other' => $groupedCosts->get('other')['total'] ?? 0,
        ];

        $stats['total'] = array_sum($stats);

        // Budget comparison
        $budget = $project->budget ?? 0;
        $budgetUsed = $stats['total'];
        $budgetRemaining = $budget - $budgetUsed;
        $budgetPercentage = $budget > 0 ? ($budgetUsed / $budget) * 100 : 0;

        // Chart data for cost breakdown
        $chartData = [
            'labels' => [
                __('messages.labor'),
                __('messages.material'),
                __('messages.equipment'),
                __('messages.other')
            ],
            'values' => [
                $stats['labor'],
                $stats['material'],
                $stats['equipment'],
                $stats['other']
            ]
        ];

        return view('backend.financial.costs.index', compact(
            'project',
            'costs',
            'stats',
            'budget',
            'budgetUsed',
            'budgetRemaining',
            'budgetPercentage',
            'chartData'
        ));
    }

    /**
     * Show the form for creating a new project cost.
     */
    public function create(Project $project): View
    {
        return view('backend.financial.costs.create', compact('project'));
    }

    /**
     * Store a newly created project cost in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'cost_date' => 'required|date',
            'cost_type' => 'required|in:labor,material,equipment,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
        ]);

        ProjectCost::create($validated);

        return redirect()->route('backend.costs.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified project cost.
     */
    public function show(ProjectCost $cost): View
    {
        $cost->load('project');

        return view('backend.financial.costs.show', compact('cost'));
    }

    /**
     * Show the form for editing the specified project cost.
     */
    public function edit(ProjectCost $cost): View
    {
        $project = $cost->project;

        return view('backend.financial.costs.edit', compact('cost', 'project'));
    }

    /**
     * Update the specified project cost in storage.
     */
    public function update(Request $request, ProjectCost $cost): RedirectResponse
    {
        $validated = $request->validate([
            'cost_date' => 'required|date',
            'cost_type' => 'required|in:labor,material,equipment,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
        ]);

        $cost->update($validated);

        return redirect()->route('backend.costs.index', $cost->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified project cost from storage.
     */
    public function destroy(ProjectCost $cost): RedirectResponse
    {
        $projectId = $cost->project_id;
        $cost->delete();

        return redirect()->route('backend.costs.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
