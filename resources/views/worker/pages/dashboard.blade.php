@extends('backend.layouts.master')

@section('title', __('messages.worker_dashboard'))
@section('page-title', __('messages.worker_dashboard'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="card">
        <div class="p-6">
            <h4 class="text-2xl font-semibold text-gray-900 mb-2">
                {{ __('messages.worker_welcome', ['name' => $worker->name]) }}
            </h4>
            <p class="text-sm text-gray-500">{{ __('messages.worker_dashboard_intro') }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <a href="{{ route('worker.projects.index', ['action' => 'report']) }}" class="card hover:shadow-lg transition">
            <div class="p-5 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <i class="uil uil-clipboard-notes text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.add_daily_report') }}</p>
                    <h5 class="font-semibold text-gray-900">{{ __('messages.quick_action') }}</h5>
                </div>
            </div>
        </a>
        <a href="{{ route('worker.projects.index', ['action' => 'material']) }}" class="card hover:shadow-lg transition">
            <div class="p-5 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center">
                    <i class="uil uil-truck text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.add_material_delivery') }}</p>
                    <h5 class="font-semibold text-gray-900">{{ __('messages.quick_action') }}</h5>
                </div>
            </div>
        </a>
        <a href="{{ route('worker.projects.index', ['action' => 'photo']) }}" class="card hover:shadow-lg transition">
            <div class="p-5 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center">
                    <i class="uil uil-camera text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.upload_site_photos') }}</p>
                    <h5 class="font-semibold text-gray-900">{{ __('messages.quick_action') }}</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.assigned_projects') }}</p>
                <h3 class="text-3xl font-bold text-primary">{{ $stats['total_projects'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.active_projects') }}</p>
                <h3 class="text-3xl font-bold text-success">{{ $stats['active_projects'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.completed_projects') }}</p>
                <h3 class="text-3xl font-bold text-info">{{ $stats['completed_projects'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.reports_created') }}</p>
                <h3 class="text-3xl font-bold text-warning">{{ $stats['reports'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.photos_uploaded') }}</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['photos'] }}</h3>
            </div>
        </div>
    </div>

    @if($assignedProjects->count() > 0)
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h4 class="card-title">{{ __('messages.my_projects') }}</h4>
            <a href="{{ route('worker.projects.index') }}" class="btn btn-sm btn-light">{{ __('messages.view_all') }}</a>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assignedProjects->take(6) as $project)
                <a href="{{ route('worker.projects.show', $project) }}" class="card hover:shadow-lg transition">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-semibold text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                            </h5>
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
                        </div>
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="uil uil-user me-1"></i>{{ $project->client->name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ __('messages.my_role') }}: {{ app()->getLocale() === 'ar' && $project->pivot->role_description_ar ? $project->pivot->role_description_ar : ($project->pivot->role_description ?? '-') }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h4 class="card-title">{{ __('messages.recent_reports') }}</h4>
        </div>
        <div class="p-6 space-y-4">
            @forelse($recentReports as $report)
                <div class="flex items-center justify-between border rounded-lg p-4">
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ app()->getLocale() === 'ar' ? $report->project?->name_ar : $report->project?->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ __('messages.report_date') }}: {{ $report->report_date?->format('Y-m-d') }}
                        </p>
                    </div>
                    <span class="text-sm font-medium text-primary">{{ number_format($report->completion_percentage, 0) }}%</span>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="uil uil-clipboard-notes text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">{{ __('messages.no_reports_yet') }}</p>
                </div>
            @endforelse
        </div>
    </div>
    @else
    <div class="card">
        <div class="p-12 text-center">
            <i class="uil uil-briefcase text-6xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_projects_assigned') }}</h4>
            <p class="text-gray-500">{{ __('messages.no_projects_assigned_message') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
