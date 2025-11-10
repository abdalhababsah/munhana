<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'clients' => User::where('role', 'client')->count(),
            'workers' => User::where('role', 'worker')->count(),
        ];

        return view('backend.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,client,worker',
            'phone' => 'nullable|string|max:20',
            'language' => 'required|in:ar,en',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('backend.users.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('createdProjects');

        $userData = [
            'projects' => collect(),
            'projects_count' => 0,
            'active_projects' => 0,
            'reports_count' => 0,
        ];

        if ($user->role === 'client') {
            // Get projects created by this client
            $projects = $user->createdProjects()->withCount('dailyReports')->get();
            $userData['projects'] = $projects;
            $userData['projects_count'] = $projects->count();
            $userData['active_projects'] = $projects->whereIn('status', ['active', 'in_progress'])->count();
        } elseif ($user->role === 'worker') {
            // Get projects this worker has contributed to via daily reports
            $projectIds = DailyReport::where('created_by', $user->id)
                ->distinct()
                ->pluck('project_id');

            $projects = Project::whereIn('id', $projectIds)
                ->withCount('dailyReports')
                ->get();

            $userData['projects'] = $projects;
            $userData['projects_count'] = $projects->count();
            $userData['reports_count'] = DailyReport::where('created_by', $user->id)->count();
        }

        return view('backend.users.show', compact('user', 'userData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('backend.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,client,worker',
            'phone' => 'nullable|string|max:20',
            'language' => 'required|in:ar,en',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('backend.users.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('backend.users.index')
                ->with('error', __('messages.cannot_delete_self'));
        }

        // If user is client with active projects, prevent deletion
        if ($user->role === 'client') {
            $activeProjects = $user->createdProjects()
                ->whereIn('status', ['active', 'in_progress'])
                ->count();

            if ($activeProjects > 0) {
                return redirect()->route('backend.users.index')
                    ->with('error', __('messages.cannot_delete_client_with_active_projects'));
            }
        }

        $user->delete();

        return redirect()->route('backend.users.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
