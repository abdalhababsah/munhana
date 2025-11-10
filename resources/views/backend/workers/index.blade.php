@extends('backend.layouts.master')

@section('title', __('messages.workers_management'))
@section('page-title', __('messages.workers_management'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.workers_management') }}</h4>
        <a href="{{ route('backend.users.create') }}" class="btn btn-primary">
            <i class="uil uil-plus me-2"></i>
            {{ __('messages.new_worker') }}
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_workers') }}</p>
                        <h3 class="text-2xl font-bold text-warning">{{ $workers->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-hard-hat text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.assigned_workers') }}</p>
                        <h3 class="text-2xl font-bold text-success">{{ $workers->where('assigned_projects_count', '>', 0)->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.unassigned_workers') }}</p>
                        <h3 class="text-2xl font-bold text-gray-600">{{ $workers->where('assigned_projects_count', 0)->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="uil uil-user-minus text-gray-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_assignments') }}</p>
                        <h3 class="text-2xl font-bold text-primary">{{ $workers->sum('assigned_projects_count') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-briefcase text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workers List -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.all_workers') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($workers->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.worker') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.contact') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.projects_assigned') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('messages.current_projects') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($workers as $worker)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center me-3">
                                    <span class="text-sm font-semibold text-warning">{{ strtoupper(substr($worker->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $worker->name }}</p>
                                    <p class="text-xs text-gray-500">{{ __('messages.joined') }}: {{ $worker->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $worker->email }}</p>
                            @if($worker->phone)
                            <p class="text-xs text-gray-500">{{ $worker->phone }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-lg font-bold {{ $worker->assigned_projects_count > 0 ? 'text-primary' : 'text-gray-400' }}">
                                {{ $worker->assigned_projects_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($worker->assignedProjects->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($worker->assignedProjects->take(2) as $project)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $project->status === 'active' || $project->status === 'in_progress' ? 'bg-success/10 text-success' : 'bg-gray-100 text-gray-600' }}">
                                        {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                                    </span>
                                    @endforeach
                                    @if($worker->assignedProjects->count() > 2)
                                    <span class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary">
                                        +{{ $worker->assignedProjects->count() - 2 }}
                                    </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-500">{{ __('messages.not_assigned') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.workers.show', $worker) }}"
                                   class="text-info hover:text-info/80"
                                   title="{{ __('messages.view') }}">
                                    <i class="uil uil-eye text-lg"></i>
                                </a>
                                <a href="{{ route('backend.users.edit', $worker) }}"
                                   class="text-warning hover:text-warning/80"
                                   title="{{ __('messages.edit') }}">
                                    <i class="uil uil-edit text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-hard-hat text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_workers_found') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
