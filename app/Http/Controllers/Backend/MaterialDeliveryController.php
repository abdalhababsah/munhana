<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MaterialDelivery;
use App\Models\Project;
use App\Models\BoqItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MaterialDeliveryController extends Controller
{
    /**
     * Display a listing of material deliveries for a project.
     */
    public function index(Project $project): View
    {
        $deliveries = $project->materialDeliveries()
            ->with('boqItem')
            ->orderBy('delivery_date', 'desc')
            ->get();

        // Group by BOQ item to calculate totals
        $groupedDeliveries = $deliveries->groupBy('boq_item_id')->map(function ($items) {
            $boqItem = $items->first()->boqItem;
            return [
                'boq_item' => $boqItem,
                'total_delivered' => $items->sum('quantity_delivered'),
                'deliveries' => $items,
            ];
        });

        // Calculate statistics
        $stats = [
            'total_deliveries' => $deliveries->count(),
            'total_items' => $groupedDeliveries->count(),
            'total_value_delivered' => $deliveries->sum(function ($delivery) {
                return $delivery->quantity_delivered * $delivery->boqItem->unit_price;
            }),
        ];

        return view('backend.reports.materials.index', compact('project', 'groupedDeliveries', 'stats'));
    }

    /**
     * Show the form for creating a new material delivery.
     */
    public function create(Project $project): View
    {
        $boqItems = $project->boqItems()->orderBy('item_code')->get();

        return view('backend.reports.materials.create', compact('project', 'boqItems'));
    }

    /**
     * Store a newly created material delivery in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'boq_item_id' => 'required|exists:boq_items,id',
            'delivery_date' => 'required|date',
            'quantity_delivered' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'received_by' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        // Create delivery
        MaterialDelivery::create($validated);

        // Update BOQ item executed_quantity
        $boqItem = BoqItem::find($validated['boq_item_id']);
        $boqItem->executed_quantity += $validated['quantity_delivered'];
        $boqItem->save();

        return redirect()->route('backend.materials.index', $validated['project_id'])
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified material delivery.
     */
    public function edit(MaterialDelivery $material): View
    {
        $project = $material->project;
        $boqItems = $project->boqItems()->orderBy('item_code')->get();

        return view('backend.reports.materials.edit', compact('material', 'project', 'boqItems'));
    }

    /**
     * Update the specified material delivery in storage.
     */
    public function update(Request $request, MaterialDelivery $material): RedirectResponse
    {
        $oldQuantity = $material->quantity_delivered;

        $validated = $request->validate([
            'boq_item_id' => 'required|exists:boq_items,id',
            'delivery_date' => 'required|date',
            'quantity_delivered' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'received_by' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'notes_ar' => 'nullable|string',
        ]);

        // Update delivery
        $material->update($validated);

        // Update BOQ item executed_quantity (adjust the difference)
        $boqItem = BoqItem::find($validated['boq_item_id']);
        $boqItem->executed_quantity = $boqItem->executed_quantity - $oldQuantity + $validated['quantity_delivered'];
        $boqItem->save();

        return redirect()->route('backend.materials.index', $material->project_id)
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified material delivery from storage.
     */
    public function destroy(MaterialDelivery $material): RedirectResponse
    {
        $projectId = $material->project_id;
        $quantity = $material->quantity_delivered;
        $boqItemId = $material->boq_item_id;

        // Delete delivery
        $material->delete();

        // Update BOQ item executed_quantity
        $boqItem = BoqItem::find($boqItemId);
        $boqItem->executed_quantity -= $quantity;
        $boqItem->save();

        return redirect()->route('backend.materials.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
