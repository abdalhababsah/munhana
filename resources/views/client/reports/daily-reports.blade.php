@extends('backend.layouts.master')

@section('title', __('messages.daily_reports'))
@section('page-title', __('messages.daily_reports'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="card">
        <div class="p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500 mt-1">{{ $project->contract_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('client.projects.show', $project) }}" class="btn btn-light">
                    <i class="uil uil-arrow-left me-2"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.filter') }}</h4>
        </div>
        <div class="p-6">
            <form method="GET" class="grid lg:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.start_date') }}
                    </label>
                    <input type="date"
                           id="start_date"
                           name="start_date"
                           value="{{ $filters['start_date'] ?? '' }}"
                           class="form-input w-full">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.end_date') }}
                    </label>
                    <input type="date"
                           id="end_date"
                           name="end_date"
                           value="{{ $filters['end_date'] ?? '' }}"
                           class="form-input w-full">
                </div>
                <div class="flex items-end gap-3 lg:col-span-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-filter me-2"></i>
                        {{ __('messages.filter') }}
                    </button>
                    @if(!empty($filters['start_date']) || !empty($filters['end_date']))
                    <a href="{{ route('client.reports.daily', $project) }}" class="btn btn-light">
                        <i class="uil uil-times me-2"></i>
                        {{ __('messages.clear') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid md:grid-cols-4 gap-6">
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_reports') }}</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $stats['total_reports'] }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.avg_workers') }}</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ number_format($stats['avg_workers']) }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_work_hours') }}</p>
                <h3 class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_hours'], 1) }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-2">{{ __('messages.completion_percentage') }}</p>
                <h3 class="text-3xl font-bold text-orange-500">{{ number_format($stats['avg_progress'], 1) }}%</h3>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.daily_reports') }}</h4>
        </div>
        @if($dailyReports->isEmpty())
            <div class="p-12 text-center">
                <i class="uil uil-file-alt text-6xl text-gray-300 mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-900">{{ __('messages.no_reports_yet') }}</h5>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.report_date') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.worker_count') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.work_hours') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.completion_percentage') }}
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.created_by') }}
                            </th>
                            <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dailyReports as $report)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $report->report_date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $report->worker_count }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ number_format($report->work_hours, 1) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $report->completion_percentage < 50 ? 'text-danger' : ($report->completion_percentage < 80 ? 'text-warning' : 'text-success') }}">
                                {{ number_format($report->completion_percentage, 1) }}%
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $report->creator?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-end">
                                <a href="{{ route('client.reports.daily.show', $report) }}" class="btn btn-sm btn-primary">
                                    {{ __('messages.view_details') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
