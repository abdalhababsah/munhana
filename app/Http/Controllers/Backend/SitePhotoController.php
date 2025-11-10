<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SitePhoto;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SitePhotoController extends Controller
{
    /**
     * Display a listing of site photos for a project.
     */
    public function index(Request $request, Project $project): View
    {
        $query = $project->sitePhotos()->with('uploader');

        // Filter by date
        if ($request->filled('date_from')) {
            $query->where('photo_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('photo_date', '<=', $request->date_to);
        }

        $photos = $query->orderBy('photo_date', 'desc')->get();

        // Group photos by date for gallery view
        $groupedPhotos = $photos->groupBy(function ($photo) {
            return \Carbon\Carbon::parse($photo->photo_date)->format('Y-m-d');
        });

        // Calculate statistics
        $stats = [
            'total_photos' => $photos->count(),
            'total_dates' => $groupedPhotos->count(),
        ];

        return view('backend.photos.index', compact('project', 'groupedPhotos', 'stats'));
    }

    /**
     * Show the form for creating new site photos.
     */
    public function create(Project $project): View
    {
        return view('backend.photos.create', compact('project'));
    }

    /**
     * Store newly uploaded site photos in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'photo_date' => 'required|date',
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $uploadedCount = 0;

        // Loop through each photo and upload
        foreach ($request->file('photos') as $photo) {
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('site-photos', $filename, 'public');

            SitePhoto::create([
                'project_id' => $validated['project_id'],
                'photo_date' => $validated['photo_date'],
                'photo_path' => $path,
                'description' => $validated['description'] ?? null,
                'description_ar' => $validated['description_ar'] ?? null,
                'uploaded_by' => auth()->id(),
            ]);

            $uploadedCount++;
        }

        return redirect()->route('backend.photos.index', $validated['project_id'])
            ->with('success', __('messages.photos_uploaded_successfully', ['count' => $uploadedCount]));
    }

    /**
     * Display the specified site photo.
     */
    public function show(SitePhoto $photo): View
    {
        $photo->load('project', 'uploader');

        return view('backend.photos.show', compact('photo'));
    }

    /**
     * Remove the specified site photo from storage.
     */
    public function destroy(SitePhoto $photo): RedirectResponse
    {
        $projectId = $photo->project_id;

        // Delete file from storage
        if (Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        // Delete database record
        $photo->delete();

        return redirect()->route('backend.photos.index', $projectId)
            ->with('success', __('messages.deleted_successfully'));
    }
}
