<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\MaterialDelivery;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerMaterialController extends Controller
{
    public function create(Project $project): View
    {
        $worker = auth()->user();

        // Verify worker is assigned to this project
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, __('messages.not_assigned_to_project'));
        }

        $project->load('boqItems');

        return view('worker.materials.create', compact('project'));
    }

    public function store(Request $request): RedirectResponse
    {
        $worker = auth()->user();

        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'delivery_date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'supplier_name' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        // Verify worker is assigned to this project
        $project = Project::with('boqItems')->findOrFail($data['project_id']);
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('worker.dashboard')
                ->with('error', __('messages.not_assigned_to_project'));
        }

        if (! $project->boqItems()->whereKey($data['boq_item_id'])->exists()) {
            return back()->withErrors(['boq_item_id' => __('messages.invalid_boq_item')])->withInput();
        }

        $data['received_by'] = $data['received_by'] ?: $worker->name;

        MaterialDelivery::create($data);

        return redirect()->route('worker.dashboard')->with('success', __('messages.material_delivery_created_successfully'));
    }
}
