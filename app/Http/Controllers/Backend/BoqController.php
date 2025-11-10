<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BoqController extends Controller
{
    /**
     * Display a listing of all BOQ items from all projects.
     */
    public function allItems(): View
    {
        $boqItems = BoqItem::with('project')->orderBy('created_at', 'desc')->paginate(20);

        // Calculate totals
        $stats = [
            'total_items' => BoqItem::count(),
            'total_value' => BoqItem::sum('total_price'),
            'total_quantity' => BoqItem::sum('total_quantity'),
            'total_executed_quantity' => BoqItem::sum('executed_quantity'),
            'overall_completion' => BoqItem::sum('total_quantity') > 0
                ? (BoqItem::sum('executed_quantity') / BoqItem::sum('total_quantity')) * 100
                : 0,
        ];

        return view('backend.boq.all', compact('boqItems', 'stats'));
    }

    /**
     * Display a listing of BOQ items for a project.
     */
    public function index(Project $project): View
    {
        $boqItems = $project->boqItems()->orderBy('item_code')->get();

        // Calculate totals
        $stats = [
            'total_items' => $boqItems->count(),
            'total_quantity' => $boqItems->sum('total_quantity'),
            'total_executed_quantity' => $boqItems->sum('executed_quantity'),
            'total_value' => $boqItems->sum('total_price'),
            'overall_completion' => $boqItems->sum('total_quantity') > 0
                ? ($boqItems->sum('executed_quantity') / $boqItems->sum('total_quantity')) * 100
                : 0,
        ];

        return view('backend.boq.index', compact('project', 'boqItems', 'stats'));
    }

    /**
     * Show the form for creating a new BOQ item.
     */
    public function create(Project $project): View
    {
        return view('backend.boq.create', compact('project'));
    }

    /**
     * Store a newly created BOQ item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'item_code' => 'required|string|max:50',
            'item_name' => 'required|string|max:255',
            'item_name_ar' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_ar' => 'required|string|max:50',
            'total_quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'approved_supplier' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'specifications_ar' => 'nullable|string',
        ]);

        // Calculate total price
        $validated['total_price'] = $validated['total_quantity'] * $validated['unit_price'];
        $validated['executed_quantity'] = 0;

        BoqItem::create($validated);

        return redirect()->route('backend.boq.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified BOQ item.
     */
    public function edit(BoqItem $boq): View
    {
        $project = $boq->project;

        // Calculate completion percentage for this item
        $boq->completion_percentage = $boq->total_quantity > 0
            ? ($boq->executed_quantity / $boq->total_quantity) * 100
            : 0;

        return view('backend.boq.edit', compact('boq', 'project'));
    }

    /**
     * Update the specified BOQ item in storage.
     */
    public function update(Request $request, BoqItem $boq): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:50',
            'item_name' => 'required|string|max:255',
            'item_name_ar' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_ar' => 'required|string|max:50',
            'total_quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'approved_supplier' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'specifications_ar' => 'nullable|string',
        ]);

        // Recalculate total price
        $validated['total_price'] = $validated['total_quantity'] * $validated['unit_price'];

        $boq->update($validated);

        return redirect()->route('backend.boq.index', $boq->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified BOQ item from storage.
     */
    public function destroy(BoqItem $boq): RedirectResponse
    {
        // Check if item is linked to timeline activities
        $hasTimelineActivities = $boq->project->timelines()
            ->where('boq_item_id', $boq->id)
            ->exists();

        // Check if has material deliveries
        $hasMaterialDeliveries = $boq->materialDeliveries()->exists();

        if ($hasTimelineActivities || $hasMaterialDeliveries) {
            return redirect()->route('backend.boq.index', $boq->project_id)
                ->with('error', __('Cannot delete BOQ item. It is linked to timeline activities or material deliveries.'));
        }

        $projectId = $boq->project_id;
        $boq->delete();

        return redirect()->route('backend.boq.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
