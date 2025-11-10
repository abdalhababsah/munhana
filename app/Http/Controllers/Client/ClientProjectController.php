<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientProjectController extends Controller
{
    /**
     * Display a listing of the client's projects.
     */
    public function index(Request $request): View
    {
        $client = auth()->user();

        $query = Project::where('client_id', $client->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('contract_number', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total' => $projects->count(),
            'active' => $projects->whereIn('status', ['active', 'in_progress'])->count(),
            'completed' => $projects->where('status', 'completed')->count(),
        ];

        return view('client.projects.index', compact('projects', 'stats'));
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): View|RedirectResponse
    {
        $client = auth()->user();

        // Verify ownership - client can only view their own projects
        if ($project->client_id !== $client->id) {
            return redirect()->route('client.projects.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        // Load all relationships
        $project->load([
            'boqItems',
            'timelines' => function ($query) {
                $query->orderBy('planned_start_date');
            },
            'dailyReports' => function ($query) {
                $query->with('creator')->orderBy('report_date', 'desc');
            },
            'materialDeliveries' => function ($query) {
                $query->orderBy('delivery_date', 'desc');
            },
            'siteVisits' => function ($query) {
                $query->with('visitor')->orderBy('visit_date', 'desc');
            },
            'weeklyPlans' => function ($query) {
                $query->orderBy('week_start_date', 'desc');
            },
            'sitePhotos' => function ($query) {
                $query->orderBy('photo_date', 'desc');
            },
            'financialClaims' => function ($query) {
                $query->orderBy('claim_date', 'desc');
            },
            'projectCosts' => function ($query) {
                $query->orderBy('date', 'desc');
            },
        ]);

        // Calculate statistics
        $stats = [
            'total_boq_items' => $project->boqItems->count(),
            'total_boq_cost' => $project->boqItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            }),
            'total_activities' => $project->timelines->count(),
            'completed_activities' => $project->timelines->where('status', 'completed')->count(),
            'total_reports' => $project->dailyReports->count(),
            'avg_workers' => $project->dailyReports->avg('worker_count') ?? 0,
            'total_photos' => $project->sitePhotos->count(),
            'total_claims' => $project->financialClaims->count(),
            'approved_claims' => $project->financialClaims->where('status', 'approved')->count(),
            'total_costs' => $project->projectCosts->sum('amount'),
        ];

        return view('client.projects.show', compact('project', 'stats'));
    }
}
