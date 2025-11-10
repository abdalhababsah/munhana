<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Project::with('client');

        // Search by name, name_ar, or contract_number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('contract_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        // Get all clients for filter dropdown
        $clients = User::where('role', 'client')->orderBy('name')->get();

        return view('backend.projects.index', compact('projects', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clients = User::where('role', 'client')->get();
        return view('backend.projects.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'contract_number' => 'required|string|unique:projects,contract_number',
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_budget' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,archived',
            'location' => 'required|string',
            'location_ar' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        $validated['created_by'] = auth()->id();

        Project::create($validated);

        return redirect()->route('backend.projects.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load('client', 'creator', 'boqItems', 'dailyReports', 'timelines', 'projectCosts');

        // Calculate project statistics
        $stats = [
            'total_boq_value' => $project->boqItems->sum('total_price'),
            'total_executed_qty' => $project->boqItems->sum('executed_quantity'),
            'total_qty' => $project->boqItems->sum('total_quantity'),
            'execution_percentage' => $project->boqItems->sum('total_quantity') > 0
                ? ($project->boqItems->sum('executed_quantity') / $project->boqItems->sum('total_quantity')) * 100
                : 0,
            'total_costs' => $project->projectCosts->sum('amount'),
            'daily_reports_count' => $project->dailyReports->count(),
            'days_since_start' => now()->diffInDays($project->start_date),
            'days_remaining' => $project->end_date->diffInDays(now(), false),
            'total_duration' => $project->start_date->diffInDays($project->end_date),
        ];

        return view('backend.projects.show', compact('project', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $clients = User::where('role', 'client')->get();
        return view('backend.projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'contract_number' => 'required|string|unique:projects,contract_number,' . $project->id,
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_budget' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,archived',
            'location' => 'required|string',
            'location_ar' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        if ($request->hasFile('contract_file')) {
            // Delete old contract file if exists
            if ($project->contract_file && \Storage::disk('public')->exists($project->contract_file)) {
                \Storage::disk('public')->delete($project->contract_file);
            }
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        $project->update($validated);

        return redirect()->route('backend.projects.show', $project)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        // Check if project has related data
        $hasRelatedData = $project->boqItems()->exists() ||
                          $project->dailyReports()->exists() ||
                          $project->timelines()->exists() ||
                          $project->materialDeliveries()->exists();

        if ($hasRelatedData) {
            // Soft delete if has related data
            $project->delete();
            $message = __('Project archived successfully. Related data preserved.');
        } else {
            // Can safely delete
            $project->forceDelete();
            $message = __('messages.deleted_successfully');
        }

        return redirect()->route('backend.projects.index')
            ->with('success', $message);
    }
}
