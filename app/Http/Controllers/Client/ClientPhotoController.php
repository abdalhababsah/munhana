<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SitePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPhotoController extends Controller
{
    /**
     * Display a gallery of site photos for the specified project.
     */
    public function index(Request $request, int $projectId): View|RedirectResponse
    {
        $project = Project::findOrFail($projectId);

        if ($redirect = $this->authorizeProject($project)) {
            return $redirect;
        }

        $photosQuery = SitePhoto::where('project_id', $project->id)
            ->orderBy('photo_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $photosQuery->whereDate('photo_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $photosQuery->whereDate('photo_date', '<=', $request->end_date);
        }

        $photos = $photosQuery->get();

        $photosByDate = $photos->groupBy(function (SitePhoto $photo) {
            return optional($photo->photo_date)->format('Y-m-d') ?? $photo->created_at->format('Y-m-d');
        });

        return view('client.photos.gallery', [
            'project' => $project,
            'photosByDate' => $photosByDate,
            'filters' => $request->only(['start_date', 'end_date']),
        ]);
    }

    /**
     * Display a single photo in lightbox mode.
     */
    public function show(int $photoId): View|RedirectResponse
    {
        $photo = SitePhoto::with('project', 'uploader')->findOrFail($photoId);

        if ($redirect = $this->authorizeProject($photo->project)) {
            return $redirect;
        }

        return view('client.photos.show', [
            'project' => $photo->project,
            'photo' => $photo,
        ]);
    }

    /**
     * Ensure the authenticated client owns the project.
     */
    protected function authorizeProject(Project $project): ?RedirectResponse
    {
        if ($project->client_id !== auth()->id()) {
            return redirect()
                ->route('client.projects.index')
                ->with('error', __('messages.unauthorized_access'));
        }

        return null;
    }
}
