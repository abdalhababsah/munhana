<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SitePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerPhotoController extends Controller
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

        return view('worker.photos.create', compact('project'));
    }

    public function store(Request $request): RedirectResponse
    {
        $worker = auth()->user();

        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'photo_date' => 'required|date',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ]);

        // Verify worker is assigned to this project
        $project = Project::findOrFail($data['project_id']);
        $isAssigned = $worker->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('worker.dashboard')
                ->with('error', __('messages.not_assigned_to_project'));
        }

        $count = 0;

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('site-photos', 'public');

            SitePhoto::create([
                'project_id' => $data['project_id'],
                'photo_date' => $data['photo_date'],
                'description' => $data['description'] ?? null,
                'description_ar' => $data['description_ar'] ?? null,
                'photo_path' => $path,
                'uploaded_by' => $worker->id,
            ]);

            $count++;
        }

        return redirect()->route('worker.dashboard')
            ->with('success', __('messages.photos_uploaded_successfully', ['count' => $count]));
    }
}
