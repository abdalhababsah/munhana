<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\WarrantyIssue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WarrantyIssueController extends Controller
{
    /**
     * Display a list of completed projects for quick navigation.
     */
    public function projectSelection(): View
    {
        $projects = Project::where('status', 'completed')->orderBy('name')->get();

        return view('backend.warranty.issues.projects', compact('projects'));
    }

    /**
     * Display a listing of the warranty issues for the given project.
     */
    public function index(Project $project, Request $request): View
    {
        $status = $request->get('status');
        $query = $project->warrantyIssues()->with('reporter')->orderByDesc('issue_date');

        if ($status) {
            $query->where('status', $status);
        }

        $issues = $query->get();

        $stats = [
            'total' => $project->warrantyIssues()->count(),
            'open' => $project->warrantyIssues()->where('status', 'open')->count(),
            'in_progress' => $project->warrantyIssues()->where('status', 'in_progress')->count(),
            'resolved' => $project->warrantyIssues()->where('status', 'resolved')->count(),
        ];

        return view('backend.warranty.issues.index', compact('project', 'issues', 'stats', 'status'));
    }

    /**
     * Show the form for creating a new warranty issue for the given project.
     */
    public function create(Project $project): View
    {
        return view('backend.warranty.issues.create', compact('project'));
    }

    /**
     * Store a newly created warranty issue.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'issue_title' => 'required|string|max:255',
            'issue_title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'photo_path' => 'nullable|image|max:5120',
        ]);

        $data['reported_by'] = $request->user()->id;
        $data['issue_date'] = now();
        $data['status'] = 'open';

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('warranty-issues', 'public');
        }

        WarrantyIssue::create($data);

        return redirect()
            ->route('backend.warranty-issues.index', $data['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified warranty issue with comments.
     */
    public function show(WarrantyIssue $warrantyIssue): View
    {
        $warrantyIssue->load(['project', 'reporter', 'comments.user']);

        return view('backend.warranty.issues.show', [
            'project' => $warrantyIssue->project,
            'issue' => $warrantyIssue,
            'comments' => $warrantyIssue->comments,
        ]);
    }

    /**
     * Update the specified warranty issue.
     */
    public function update(Request $request, WarrantyIssue $warrantyIssue): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($data['status'] === 'resolved') {
            $warrantyIssue->resolved_date = $warrantyIssue->resolved_date ?? now();
        } else {
            $warrantyIssue->resolved_date = null;
            $data['resolution_notes'] = $data['resolution_notes'] ?? null;
        }

        $warrantyIssue->status = $data['status'];
        $warrantyIssue->resolution_notes = $data['resolution_notes'];
        $warrantyIssue->save();

        return redirect()
            ->route('backend.warranty-issues.show', $warrantyIssue)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified warranty issue.
     */
    public function destroy(WarrantyIssue $warrantyIssue): RedirectResponse
    {
        $projectId = $warrantyIssue->project_id;

        if ($warrantyIssue->photo_path && Storage::disk('public')->exists($warrantyIssue->photo_path)) {
            Storage::disk('public')->delete($warrantyIssue->photo_path);
        }

        $warrantyIssue->delete();

        return redirect()
            ->route('backend.warranty-issues.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
