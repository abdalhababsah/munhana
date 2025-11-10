@extends('backend.layouts.master')

@section('title', __('messages.daily_reports') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.daily_reports'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Project Header -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.reports.daily.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new_report') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Total Reports -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_reports') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $stats['total_reports'] }}
                        </h3>
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
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['avg_workers'], 1) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-users-alt text-success text-2xl"></i>
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
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['avg_work_hours'], 1) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clock text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Completion -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.current_completion') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['current_completion'], 1) }}%
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-chart-pie text-warning text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $stats['current_completion'] < 50 ? 'bg-danger' : ($stats['current_completion'] < 80 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ min($stats['current_completion'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="p-6">
            <form method="GET" action="{{ route('backend.reports.daily.index', $project) }}" class="grid lg:grid-cols-4 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_from') }}
                    </label>
                    <input type="date"
                           id="date_from"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="form-input w-full">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.date_to') }}
                    </label>
                    <input type="date"
                           id="date_to"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="form-input w-full">
                </div>
                <div class="flex items-end gap-2 lg:col-span-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="uil uil-filter me-2"></i>
                        {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('backend.reports.daily.index', $project) }}" class="btn btn-light">
                        <i class="uil uil-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Daily Reports Table -->
    <div class="card">
        <div class="p-6">
            @if(session('success'))
            <div class="mb-4 p-4 bg-success/10 text-success rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.report_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.workers') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.work_hours') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.completion') }} %
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.notes') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.created_by') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->worker_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->work_hours }}h</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-1">
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
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate">
                                    {{ app()->getLocale() === 'ar' ? ($report->notes_ar ?? $report->notes) : $report->notes }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->creator->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('backend.reports.daily.show', $report) }}"
                                       class="text-info hover:text-info-dark"
                                       title="{{ __('messages.view') }}">
                                        <i class="uil uil-eye text-lg"></i>
                                    </a>
                                    <a href="{{ route('backend.reports.daily.edit', $report) }}"
                                       class="text-warning hover:text-warning-dark"
                                       title="{{ __('messages.edit') }}">
                                        <i class="uil uil-edit text-lg"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('backend.reports.daily.destroy', $report) }}"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-danger hover:text-danger-dark"
                                                title="{{ __('messages.delete') }}">
                                            <i class="uil uil-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="uil uil-file-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_data') }}</p>
                <a href="{{ route('backend.reports.daily.create', $project) }}" class="btn btn-primary mt-4">
                    <i class="uil uil-plus me-2"></i>
                    {{ __('messages.new_report') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
