<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\WarrantyIssue;
use App\Models\User;
use App\Notifications\ClientIssueReported;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ClientIssueController extends Controller
{
    /**
     * Display a listing of the client's reported issues.
     */
    public function index(Request $request): View
    {
        $client = $request->user();
        $status = $request->get('status');

        $issuesQuery = WarrantyIssue::with('project')
            ->where('reported_by', $client->id)
            ->orderByDesc('issue_date');

        if ($status) {
            $issuesQuery->where('status', $status);
        }

        $issues = $issuesQuery->get();

        $baseQuery = WarrantyIssue::where('reported_by', $client->id);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
        ];

        $groupedIssues = $issues->groupBy('project_id');

        return view('client.issues.index', compact('issues', 'groupedIssues', 'stats', 'status'));
    }

    /**
     * Display eligible projects to report a new issue.
     */
    public function projects(Request $request): View
    {
        $client = $request->user();

        $projects = Project::where('client_id', $client->id)
            ->whereIn('status', ['completed', 'archived'])
            ->orderBy('name')
            ->get();

        return view('client.issues.projects', compact('projects'));
    }

    /**
     * Show the form for creating a new issue for a specific project.
     */
    public function create(Project $project, Request $request): RedirectResponse|View
    {
        if ($project->client_id !== $request->user()->id) {
            return redirect()->route('client.projects.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        if (!in_array($project->status, ['completed', 'archived'])) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', __('messages.issue_reporting_not_allowed'));
        }

        return view('client.issues.create', compact('project'));
    }

    /**
     * Store a newly created issue from the client.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'issue_title' => 'required|string|max:255',
            'issue_title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'photo' => 'required|image|max:5120',
        ]);

        $project = Project::findOrFail($data['project_id']);
        $client = $request->user();

        if ($project->client_id !== $client->id) {
            return redirect()->route('client.projects.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        if (!in_array($project->status, ['completed', 'archived'])) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', __('messages.issue_reporting_not_allowed'));
        }

        $photoPath = $request->file('photo')->store('warranty-issues', 'public');

        $issue = WarrantyIssue::create([
            'project_id' => $project->id,
            'reported_by' => $client->id,
            'issue_date' => now(),
            'issue_title' => $data['issue_title'],
            'issue_title_ar' => $data['issue_title_ar'],
            'description' => $data['description'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'open',
        ]);

        $issue->load('project');

        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ClientIssueReported($issue));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('client.issues.index')
            ->with('success', __('messages.issue_reported_successfully'));
    }

    /**
     * Display the specified issue.
     */
    public function show(WarrantyIssue $issue, Request $request): RedirectResponse|View
    {
        if ($issue->reported_by !== $request->user()->id) {
            return redirect()->route('client.issues.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        $issue->load(['project', 'comments.user']);

        return view('client.issues.show', [
            'issue' => $issue,
            'project' => $issue->project,
            'comments' => $issue->comments,
        ]);
    }
}
