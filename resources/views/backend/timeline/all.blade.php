@extends('backend.layouts.master')

@section('title', __('messages.timeline'))
@section('page-title', __('messages.timeline'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header with Filter -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ __('messages.timeline') }}
        </h4>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-5 md:grid-cols-3 sm:grid-cols-2 gap-6">
        <!-- Total Activities -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_activities') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_activities'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-calender text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.completed') }}</p>
                        <h3 class="text-2xl font-bold text-success">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-check-circle text-success text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.in_progress') }}</p>
                        <h3 class="text-2xl font-bold text-info">{{ $stats['in_progress'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clock text-info text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delayed -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.delayed') }}</p>
                        <h3 class="text-2xl font-bold text-danger">{{ $stats['delayed'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-danger/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-exclamation-triangle text-danger text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.pending') }}</p>
                        <h3 class="text-2xl font-bold text-warning">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-pause-circle text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="p-6">
            <form method="GET" action="{{ route('backend.timeline.all') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.filter_by_status') }}
                    </label>
                    <select id="status" name="status" class="form-select w-full">
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            {{ __('messages.pending') }}
                        </option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>
                            {{ __('messages.in_progress') }}
                        </option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                            {{ __('messages.completed') }}
                        </option>
                        <option value="delayed" {{ request('status') === 'delayed' ? 'selected' : '' }}>
                            {{ __('messages.delayed') }}
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-filter me-2"></i>
                    {{ __('messages.filter') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Timeline Activities Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.timelines') }}</h4>
        </div>
        <div class="overflow-x-auto">
            @if($timelines->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.project') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.activity_name') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.linked_boq_item') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.planned_start_date') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.planned_end_date') }}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.duration') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.status') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($timelines as $timeline)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('backend.projects.show', $timeline->project) }}" class="text-sm text-primary hover:underline">
                                {{ app()->getLocale() === 'ar' ? $timeline->project->name_ar : $timeline->project->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $timeline->activity_name_ar : $timeline->activity_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($timeline->boqItem)
                                <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">
                                    {{ $timeline->boqItem->item_code }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($timeline->planned_start_date)->format('Y-m-d') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($timeline->planned_end_date)->format('Y-m-d') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">
                                {{ $timeline->duration_days }} {{ __('messages.days') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($timeline->status === 'completed')
                                <span class="px-2 py-1 text-xs font-medium rounded bg-success/10 text-success">
                                    {{ __('messages.completed') }}
                                </span>
                            @elseif($timeline->status === 'in_progress')
                                <span class="px-2 py-1 text-xs font-medium rounded bg-info/10 text-info">
                                    {{ __('messages.in_progress') }}
                                </span>
                            @elseif($timeline->status === 'delayed')
                                <span class="px-2 py-1 text-xs font-medium rounded bg-danger/10 text-danger">
                                    {{ __('messages.delayed') }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded bg-warning/10 text-warning">
                                    {{ __('messages.pending') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.timeline.edit', $timeline) }}"
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
                {{ $timelines->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-calender text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
