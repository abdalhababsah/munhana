@extends('backend.layouts.master')

@section('title', __('messages.user') . ' - ' . $user->name)
@section('page-title', __('messages.user_details'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h4>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-warning">
                <i class="uil uil-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            @if($user->id !== auth()->id())
            <form method="POST"
                  action="{{ route('backend.users.destroy', $user) }}"
                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="uil uil-trash me-2"></i>
                    {{ __('messages.delete') }}
                </button>
            </form>
            @endif
            <a href="{{ route('backend.users.index') }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.user_information') }}</h4>
                </div>
                <div class="p-6">
                    <!-- Avatar -->
                    <div class="flex flex-col items-center mb-6 pb-6 border-b">
                        <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center mb-3">
                            <span class="text-3xl font-bold text-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}</h5>
                        @if($user->role === 'admin')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-danger/10 text-danger">
                                {{ __('messages.admin') }}
                            </span>
                        @elseif($user->role === 'client')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-success/10 text-success">
                                {{ __('messages.client') }}
                            </span>
                        @elseif($user->role === 'worker')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-warning/10 text-warning">
                                {{ __('messages.worker') }}
                            </span>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.email') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->email }}</p>
                        </div>

                        @if($user->phone)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.phone') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->phone }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.language') }}</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $user->language === 'ar' ? __('messages.arabic') : __('messages.english') }}
                            </p>
                        </div>

                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-1">{{ __('messages.joined') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-Specific Information -->
        <div class="lg:col-span-2">
            @if($user->role === 'client')
            <!-- Client Projects -->
            <div class="card mb-6">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.project_statistics') }}</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-primary/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.total_projects') }}</p>
                            <p class="text-2xl font-bold text-primary">{{ $userData['projects_count'] }}</p>
                        </div>
                        <div class="bg-success/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.active_projects') }}</p>
                            <p class="text-2xl font-bold text-success">{{ $userData['active_projects'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($userData['projects']->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.projects') }}</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.project') }}
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.status') }}
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.reports') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($userData['projects'] as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $project->contract_number }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($project->status === 'active' || $project->status === 'in_progress')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                                            {{ __('messages.' . $project->status) }}
                                        </span>
                                    @elseif($project->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                                            {{ __('messages.completed') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                            {{ __('messages.' . $project->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $project->daily_reports_count }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('backend.projects.show', $project) }}"
                                       class="text-primary hover:text-primary/80">
                                        <i class="uil uil-eye text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="card">
                <div class="p-12 text-center">
                    <i class="uil uil-folder-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">{{ __('messages.no_projects_yet') }}</p>
                </div>
            </div>
            @endif

            @elseif($user->role === 'worker')
            <!-- Worker Statistics -->
            <div class="card mb-6">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.work_statistics') }}</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-primary/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.projects_contributed') }}</p>
                            <p class="text-2xl font-bold text-primary">{{ $userData['projects_count'] }}</p>
                        </div>
                        <div class="bg-warning/5 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.reports_created') }}</p>
                            <p class="text-2xl font-bold text-warning">{{ $userData['reports_count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($userData['projects']->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.projects_worked_on') }}</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.project') }}
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.status') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($userData['projects'] as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $project->contract_number }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($project->status === 'active' || $project->status === 'in_progress')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                                            {{ __('messages.' . $project->status) }}
                                        </span>
                                    @elseif($project->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                                            {{ __('messages.completed') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                            {{ __('messages.' . $project->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('backend.projects.show', $project) }}"
                                       class="text-primary hover:text-primary/80">
                                        <i class="uil uil-eye text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="card">
                <div class="p-12 text-center">
                    <i class="uil uil-folder-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">{{ __('messages.no_projects_yet') }}</p>
                </div>
            </div>
            @endif

            @else
            <!-- Admin - No specific data -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.administrator') }}</h4>
                </div>
                <div class="p-12 text-center">
                    <i class="uil uil-shield-check text-6xl text-danger mb-4"></i>
                    <p class="text-gray-500">{{ __('messages.admin_has_full_access') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
