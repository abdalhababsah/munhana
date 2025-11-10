@extends('backend.layouts.master')

@section('title', app()->getLocale() === 'ar' ? $project->name_ar : $project->name)
@section('page-title', app()->getLocale() === 'ar' ? $project->name_ar : $project->name)

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="card">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                    </h2>
                    <p class="text-gray-600 mb-4">
                        <i class="uil uil-map-marker me-2"></i>
                        {{ app()->getLocale() === 'ar' ? $project->location_ar : $project->location }}
                    </p>
                    <div class="flex items-center gap-4">
                        @if($project->status === 'active')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-success/10 text-success">
                                <i class="uil uil-check-circle me-2"></i>
                                {{ __('messages.active') }}
                            </span>
                        @elseif($project->status === 'completed')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-info/10 text-info">
                                <i class="uil uil-clipboard-alt me-2"></i>
                                {{ __('messages.completed') }}
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="uil uil-archive me-2"></i>
                                {{ __('messages.archived') }}
                            </span>
                        @endif
                        <span class="text-sm text-gray-500">
                            <i class="uil uil-user me-1"></i>
                            {{ __('messages.client') }}: {{ $project->client->name }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('backend.projects.edit', $project) }}" class="btn btn-warning">
                        <i class="uil uil-edit me-2"></i>
                        {{ __('messages.edit') }}
                    </a>
                    <a href="{{ route('backend.projects.index') }}" class="btn btn-light">
                        <i class="uil uil-arrow-left me-2"></i>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Total Budget -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_budget') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($project->total_budget, 2) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-moneybag text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOQ Value -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.boq') }} {{ __('messages.total_price') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['total_boq_value'], 2) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-file-alt text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Percentage -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.completion_percentage') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['execution_percentage'], 1) }}%
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-pie text-info text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-info h-2 rounded-full" style="width: {{ min($stats['execution_percentage'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Days Remaining -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('Days Remaining') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $stats['days_remaining'] >= 0 ? $stats['days_remaining'] : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-calendar-alt text-warning text-2xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    {{ __('messages.of') }} {{ $stats['total_duration'] }} {{ __('messages.duration_days') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Overview Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-info-circle text-primary"></i>
                    {{ __('messages.overview') }}
                </h4>
            </div>
        </div>
        <div class="p-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Project Information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.project_details') }}</h4>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.contract_number') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->contract_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.start_date') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->start_date->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.end_date') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->end_date->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.created_by') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->creator->name }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Statistics -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.statistics') }}</h4>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.boq_items') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->boqItems->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.daily_reports') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $stats['daily_reports_count'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.timelines') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $project->timelines->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('messages.project_costs') }}</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($stats['total_costs'], 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($project->description || $project->description_ar)
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.description') }}</h4>
                <p class="text-gray-600">
                    {{ app()->getLocale() === 'ar' ? $project->description_ar : $project->description }}
                </p>
            </div>
            @endif

            @if($project->contract_file)
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.contract_file') }}</h4>
                <a href="{{ asset('storage/' . $project->contract_file) }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm text-gray-700">
                    <i class="uil uil-file-download me-2"></i>
                    {{ __('messages.download_contract') }}
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Project Details Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-file-info-alt text-primary"></i>
                    {{ __('messages.project_details') }}
                </h4>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.project_name') }} (English)</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project->name }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.project_name') }} (العربية)</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project->name_ar }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.location') }} (English)</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project->location }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.location') }} (العربية)</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project->location_ar }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.client') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $project->client->name }}<br>
                                <span class="text-gray-500">{{ $project->client->email }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.contract_number') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project->contract_number }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.total_budget') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($project->total_budget, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500">{{ __('messages.status') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ __('messages.' . $project->status) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BOQ Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-file-alt text-primary"></i>
                    {{ __('messages.boq') }}
                </h4>
                <a href="{{ route('backend.boq.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.boq') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.manage_project_boq') }}</p>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="bg-primary/5 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">{{ __('messages.total_price') }}</p>
                    <p class="text-xl font-bold text-primary">{{ number_format($stats['total_boq_value'], 2) }}</p>
                </div>
                <div class="bg-info/5 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">{{ __('messages.boq_items') }}</p>
                    <p class="text-xl font-bold text-info">{{ $project->boqItems->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-calender text-primary"></i>
                    {{ __('messages.timeline') }}
                </h4>
                <a href="{{ route('backend.timeline.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.timeline') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.manage_project_timeline') }}</p>
            <div class="mt-4 bg-warning/5 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">{{ __('messages.timelines') }}</p>
                <p class="text-xl font-bold text-warning">{{ $project->timelines->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Daily Reports Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-clipboard-notes text-primary"></i>
                    {{ __('messages.reports') }}
                </h4>
                <a href="{{ route('backend.reports.daily.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.daily_reports') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.manage_project_reports') }}</p>
            <div class="mt-4 bg-success/5 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">{{ __('messages.daily_reports') }}</p>
                <p class="text-xl font-bold text-success">{{ $stats['daily_reports_count'] }}</p>
            </div>
        </div>
    </div>

    <!-- Material Deliveries Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-truck text-primary"></i>
                    {{ __('messages.material_deliveries') }}
                </h4>
                <a href="{{ route('backend.materials.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.material_deliveries') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.track_material_deliveries') }}</p>
        </div>
    </div>

    <!-- Site Visits Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-map-marker text-primary"></i>
                    {{ __('messages.site_visits') }}
                </h4>
                <a href="{{ route('backend.visits.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.site_visits') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.record_site_inspections') }}</p>
        </div>
    </div>

    <!-- Weekly Plans Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-calendar-alt text-primary"></i>
                    {{ __('messages.weekly_plans') }}
                </h4>
                <a href="{{ route('backend.weekly-plans.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.weekly_plans') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.plan_track_weekly_activities') }}</p>
        </div>
    </div>

    <!-- Site Photos Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-image text-primary"></i>
                    {{ __('messages.site_photos') }}
                </h4>
                <a href="{{ route('backend.photos.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.site_photos') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.upload_view_progress_photos') }}</p>
        </div>
    </div>

    <!-- Financial Claims Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-money-bill text-primary"></i>
                    {{ __('messages.financial_claims') }}
                </h4>
                <a href="{{ route('backend.claims.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.financial_claims') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.track_financial_claims_payments') }}</p>
        </div>
    </div>

    <!-- Project Costs Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center gap-2">
                    <i class="uil uil-chart-line text-primary"></i>
                    {{ __('messages.project_costs') }}
                </h4>
                <a href="{{ route('backend.costs.index', $project) }}" class="btn btn-primary">
                    <i class="uil uil-arrow-right me-2"></i>
                    {{ __('messages.view') }} {{ __('messages.project_costs') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-500">{{ __('messages.monitor_costs_budget') }}</p>
            <div class="mt-4 bg-danger/5 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">{{ __('messages.project_costs') }}</p>
                <p class="text-xl font-bold text-danger">{{ number_format($stats['total_costs'], 2) }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
