@extends('backend.layouts.master')

@section('title', __('messages.daily_reports'))
@section('page-title', __('messages.daily_reports'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ __('messages.daily_reports') }}
        </h4>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Total Reports -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_reports') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_reports'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-file-alt text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Workers -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.avg_workers') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_workers'], 1) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-users-alt text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Work Hours -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.avg_work_hours') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_work_hours'], 1) }}h</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clock text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Completion -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.current_completion') }}</p>
                        <h3 class="text-2xl font-bold {{ $stats['latest_completion'] < 50 ? 'text-danger' : ($stats['latest_completion'] < 80 ? 'text-warning' : 'text-success') }}">
                            {{ number_format($stats['latest_completion'], 1) }}%
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-pie text-warning text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $stats['latest_completion'] < 50 ? 'bg-danger' : ($stats['latest_completion'] < 80 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ min($stats['latest_completion'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="p-6">
            <form method="GET" action="{{ route('backend.reports.daily.all') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_from') }}
                    </label>
                    <input type="date"
                           id="date_from"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="form-input w-full">
                </div>
                <div class="flex-1">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_to') }}
                    </label>
                    <input type="date"
                           id="date_to"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="form-input w-full">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-filter me-2"></i>
                    {{ __('messages.filter') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Daily Reports Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.daily_reports') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($reports->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.project') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.report_date') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.workers') }}
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('backend.projects.show', $report->project) }}" class="text-sm text-primary hover:underline">
                                {{ app()->getLocale() === 'ar' ? $report->project->name_ar : $report->project->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $report->worker_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ number_format($report->work_hours, 1) }}h</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-[100px]">
                                    <div class="text-sm font-semibold {{ $report->completion_percentage < 50 ? 'text-danger' : ($report->completion_percentage < 80 ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($report->completion_percentage, 1) }}%
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="h-1.5 rounded-full {{ $report->completion_percentage < 50 ? 'bg-danger' : ($report->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                                             style="width: {{ min($report->completion_percentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $report->creator->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.reports.daily.show', $report) }}"
                                   class="text-info hover:text-info/80"
                                   title="{{ __('messages.view') }}">
                                    <i class="uil uil-eye text-lg"></i>
                                </a>
                                <a href="{{ route('backend.reports.daily.edit', $report) }}"
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

            <!-- Pagination -->
            <div class="p-6">
                {{ $reports->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-file-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
