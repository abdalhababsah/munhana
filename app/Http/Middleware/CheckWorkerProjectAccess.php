<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkerProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * Ensures that workers can only access projects they are assigned to.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is authenticated and is a worker
        if (!$user || $user->role !== 'worker') {
            return redirect()->route('worker.dashboard')
                ->with('error', __('messages.access_denied'));
        }

        // Get project from route parameter
        $project = $request->route('project');

        // If no project in route, allow access (for index pages, etc.)
        if (!$project) {
            return $next($request);
        }

        // Check if worker is assigned to this project
        $isAssigned = $user->assignedProjects()
            ->where('projects.id', $project->id)
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('worker.dashboard')
                ->with('error', __('messages.not_assigned_to_project'));
        }

        return $next($request);
    }
}
