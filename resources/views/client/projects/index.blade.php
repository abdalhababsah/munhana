@extends('backend.layouts.master')

@section('title', __('messages.my_projects'))
@section('page-title', __('messages.my_projects'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">{{ __('messages.my_projects') }}</h4>
    </div>

    <!-- Statistics Cards -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_projects') }}</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="uil uil-briefcase text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.active_projects') }}</p>
                        <h3 class="text-2xl font-bold text-success">{{ $stats['active'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-play-circle text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.completed_projects') }}</p>
                        <h3 class="text-2xl font-bold text-info">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.search_and_filter') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('client.projects.index') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.search') }}
                    </label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-input w-full"
                           placeholder="{{ __('messages.search_by_name_or_contract') }}">
                </div>
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.filter_by_status') }}
                    </label>
                    <select id="status" name="status" class="form-input w-full">
                        <option value="">{{ __('messages.all_statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>{{ __('messages.archived') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-search me-2"></i>
                    {{ __('messages.search') }}
                </button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('client.projects.index') }}" class="btn btn-light">
                    <i class="uil uil-times me-2"></i>
                    {{ __('messages.clear') }}
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Projects Grid -->
    @if($projects->count() > 0)
    <div class="grid lg:grid-cols-2 gap-6">
        @foreach($projects as $project)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="p-6">
                <!-- Project Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h5 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                        </h5>
                        <p class="text-sm text-gray-500">{{ $project->contract_number }}</p>
                    </div>
                    @if($project->status === 'active' || $project->status === 'in_progress')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @elseif($project->status === 'completed')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-info/10 text-info">
                            {{ __('messages.completed') }}
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            {{ __('messages.' . $project->status) }}
                        </span>
                    @endif
                </div>

                <!-- Project Details -->
                <div class="space-y-3 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="uil uil-calendar-alt me-2"></i>
                        <span>{{ __('messages.start') }}: {{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="uil uil-calendar-slash me-2"></i>
                        <span>{{ __('messages.end') }}: {{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}</span>
                    </div>
                    @if($project->location)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="uil uil-map-marker me-2"></i>
                        <span>{{ app()->getLocale() === 'ar' && $project->location_ar ? $project->location_ar : $project->location }}</span>
                    </div>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ __('messages.completion') }}</span>
                        <span class="text-sm font-bold {{ $project->completion_percentage < 50 ? 'text-danger' : ($project->completion_percentage < 80 ? 'text-warning' : 'text-success') }}">
                            {{ number_format($project->completion_percentage, 0) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $project->completion_percentage < 50 ? 'bg-danger' : ($project->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ min($project->completion_percentage, 100) }}%"></div>
                    </div>
                </div>

                <!-- View Button -->
                <a href="{{ route('client.projects.show', $project) }}" class="btn btn-primary w-full">
                    <i class="uil uil-eye me-2"></i>
                    {{ __('messages.view_details') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card">
        <div class="p-12 text-center">
            <i class="uil uil-folder-open text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_projects_found') }}</h5>
            <p class="text-gray-500">{{ __('messages.try_different_search') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
