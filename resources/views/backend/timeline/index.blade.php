@extends('backend.layouts.master')

@section('title', __('messages.timeline') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.timeline'))

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
            <a href="{{ route('backend.timeline.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.activity') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid lg:grid-cols-5 md:grid-cols-3 gap-6">
        <!-- Total Activities -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_activities') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $stats['total_activities'] }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-list-ul text-primary text-2xl"></i>
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
                        <h3 class="text-2xl font-bold text-success">
                            {{ $stats['completed'] }}
                        </h3>
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
                        <h3 class="text-2xl font-bold text-info">
                            {{ $stats['in_progress'] }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-sync text-info text-2xl"></i>
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
                        <h3 class="text-2xl font-bold text-danger">
                            {{ $stats['delayed'] }}
                        </h3>
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
                        <h3 class="text-2xl font-bold text-warning">
                            {{ $stats['pending'] }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-clock text-warning text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="p-6">
            <form method="GET" action="{{ route('backend.timeline.index', $project) }}" class="flex items-end gap-4">
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
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-filter me-2"></i>
                        {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('backend.timeline.index', $project) }}" class="btn btn-light">
                        <i class="uil uil-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Timeline Activities Table -->
    <div class="card">
        <div class="p-6">
            @if(session('success'))
            <div class="mb-4 p-4 bg-success/10 text-success rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if($timelines->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.activity_name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.linked_boq_item') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.planned_start_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.planned_end_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.duration') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($timelines as $timeline)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ app()->getLocale() === 'ar' ? $timeline->activity_name_ar : $timeline->activity_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($timeline->boqItem)
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $timeline->boqItem->item_code }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">
                                        {{ app()->getLocale() === 'ar' ? $timeline->boqItem->item_name_ar : $timeline->boqItem->item_name }}
                                    </span>
                                </div>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($timeline->planned_start_date)->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($timeline->planned_end_date)->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $timeline->duration_days }} {{ __('messages.days') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($timeline->status === 'completed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/10 text-success">
                                        <i class="uil uil-check-circle me-1"></i>
                                        {{ __('messages.completed') }}
                                    </span>
                                @elseif($timeline->status === 'in_progress')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-info/10 text-info">
                                        <i class="uil uil-sync me-1"></i>
                                        {{ __('messages.in_progress') }}
                                    </span>
                                @elseif($timeline->status === 'delayed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger/10 text-danger">
                                        <i class="uil uil-exclamation-triangle me-1"></i>
                                        {{ __('messages.delayed') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning/10 text-warning">
                                        <i class="uil uil-clock me-1"></i>
                                        {{ __('messages.pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('backend.timeline.edit', $timeline) }}"
                                       class="text-warning hover:text-warning-dark"
                                       title="{{ __('messages.edit') }}">
                                        <i class="uil uil-edit text-lg"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('backend.timeline.destroy', $timeline) }}"
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
                <i class="uil uil-calender text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_data') }}</p>
                <a href="{{ route('backend.timeline.create', $project) }}" class="btn btn-primary mt-4">
                    <i class="uil uil-plus me-2"></i>
                    {{ __('messages.new') }} {{ __('messages.activity') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
