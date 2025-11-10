@extends('backend.layouts.master')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Welcome Message -->
    <div class="card">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ __('messages.welcome_back') }}, {{ $client->name }}!
                    </h4>
                    <p class="text-gray-600">
                        {{ __('messages.client_dashboard_intro') }}
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="uil uil-user text-4xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.my_projects') }}</p>
                        <h3 class="text-3xl font-bold text-blue-600">{{ $stats['total_projects'] }}</h3>
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
                        <h3 class="text-3xl font-bold text-success">{{ $stats['active_projects'] }}</h3>
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
                        <h3 class="text-3xl font-bold text-info">{{ $stats['completed_projects'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.avg_completion') }}</p>
                        <h3 class="text-3xl font-bold text-warning">{{ number_format($stats['avg_completion'], 0) }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-pie text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    @if($recentProjects->count() > 0)
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">{{ __('messages.recent_projects') }}</h4>
                <a href="{{ route('client.projects.index') }}" class="text-primary hover:text-primary/80 text-sm font-medium">
                    {{ __('messages.view_all') }} →
                </a>
            </div>
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
                            {{ __('messages.completion') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentProjects as $project)
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
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $project->completion_percentage < 50 ? 'bg-danger' : ($project->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                                         style="width: {{ min($project->completion_percentage, 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($project->completion_percentage, 0) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('client.projects.show', $project) }}" class="btn btn-sm btn-primary">
                                <i class="uil uil-eye me-1"></i>
                                {{ __('messages.view_details') }}
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
            <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_projects_yet') }}</h5>
            <p class="text-gray-500">{{ __('messages.projects_will_appear_here') }}</p>
        </div>
    </div>
    @endif

    <!-- Recent Reports -->
    @if($recentReports->count() > 0)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.recent_reports') }}</h4>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentReports as $report)
                <div class="flex items-start gap-4 p-4 border rounded-lg hover:bg-gray-50">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="uil uil-file-alt text-blue-600 text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <h5 class="font-semibold text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $report->project->name_ar : $report->project->name }}
                                </h5>
                                <p class="text-sm text-gray-500">
                                    {{ __('messages.daily_report') }} - {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span><i class="uil uil-users-alt me-1"></i>{{ $report->worker_count }} {{ __('messages.workers') }}</span>
                            <span><i class="uil uil-clock me-1"></i>{{ $report->work_hours }}h</span>
                            <span><i class="uil uil-user me-1"></i>{{ $report->creator->name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('client.reports.daily.show', $report) }}" class="btn btn-sm btn-light">
                        {{ __('messages.view') }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
