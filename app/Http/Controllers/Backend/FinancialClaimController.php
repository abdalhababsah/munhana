<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FinancialClaim;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class FinancialClaimController extends Controller
{
    /**
     * Display a listing of financial claims for a project.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->financialClaims()->with('submitter');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->orderBy('claim_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_claims' => $claims->sum('amount'),
            'total_approved' => $claims->where('status', 'approved')->sum('amount') + $claims->where('status', 'paid')->sum('amount'),
            'total_paid' => $claims->where('status', 'paid')->sum('amount'),
            'pending_amount' => $claims->where('status', 'pending')->sum('amount'),
        ];

        return view('backend.financial.claims.index', compact('project', 'claims', 'stats'));
    }

    /**
     * Show the form for creating a new financial claim.
     */
    public function create(Project $project): View
    {
        // Generate next claim number
        $lastClaim = FinancialClaim::where('project_id', $project->id)
            ->orderBy('claim_number', 'desc')
            ->first();

        if ($lastClaim && preg_match('/CLM-(\d+)/', $lastClaim->claim_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $claimNumber = 'CLM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('backend.financial.claims.create', compact('project', 'claimNumber'));
    }

    /**
     * Store a newly created financial claim in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'claim_number' => 'required|string|unique:financial_claims,claim_number',
            'claim_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,paid',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx|max:10240', // 10MB max
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('claims', $filename, 'public');
            $validated['attachment_path'] = $path;
        }

        $validated['submitted_by'] = auth()->id();

        FinancialClaim::create($validated);

        return redirect()->route('backend.claims.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified financial claim.
     */
    public function show(FinancialClaim $claim): View
    {
        $claim->load('project', 'submitter');

        // Calculate running total up to this claim
        $runningTotal = FinancialClaim::where('project_id', $claim->project_id)
            ->where('claim_date', '<=', $claim->claim_date)
            ->where('id', '<=', $claim->id)
            ->sum('amount');

        return view('backend.financial.claims.show', compact('claim', 'runningTotal'));
    }

    /**
     * Show the form for editing the specified financial claim.
     */
    public function edit(FinancialClaim $claim): View
    {
        $project = $claim->project;

        return view('backend.financial.claims.edit', compact('claim', 'project'));
    }

    /**
     * Update the specified financial claim in storage.
     */
    public function update(Request $request, FinancialClaim $claim): RedirectResponse
    {
        $validated = $request->validate([
            'claim_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,paid',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($claim->attachment_path && Storage::disk('public')->exists($claim->attachment_path)) {
                Storage::disk('public')->delete($claim->attachment_path);
            }

            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('claims', $filename, 'public');
            $validated['attachment_path'] = $path;
        }

        $claim->update($validated);

        return redirect()->route('backend.claims.index', $claim->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified financial claim from storage.
     */
    public function destroy(FinancialClaim $claim): RedirectResponse
    {
        // Prevent deletion of paid claims
        if ($claim->status === 'paid') {
            return redirect()->route('backend.claims.index', $claim->project_id)
                ->with('error', __('messages.cannot_delete_paid_claim'));
        }

        $projectId = $claim->project_id;

        // Delete file if exists
        if ($claim->attachment_path && Storage::disk('public')->exists($claim->attachment_path)) {
            Storage::disk('public')->delete($claim->attachment_path);
        }

        $claim->delete();

        return redirect()->route('backend.claims.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
