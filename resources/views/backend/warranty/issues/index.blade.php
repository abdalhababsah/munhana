@extends('backend.layouts.master')

@section('title', __('messages.warranty_issues'))
@section('page-title', __('messages.warranty_issues'))

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
                <p class="text-sm text-gray-500">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('backend.warranty-issues.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.add_warranty_issue') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid md:grid-cols-4 gap-4">
        <div class="card">
            <div class="p-5">
                <p class="text-sm text-gray-500">{{ __('messages.total_issues') }}</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="card border-l-4 border-danger">
            <div class="p-5">
                <p class="text-sm text-gray-500">{{ __('messages.open_issues') }}</p>
                <h3 class="text-3xl font-bold text-danger">{{ $stats['open'] }}</h3>
            </div>
        </div>
        <div class="card border-l-4 border-warning">
            <div class="p-5">
                <p class="text-sm text-gray-500">{{ __('messages.in_progress') }}</p>
                <h3 class="text-3xl font-bold text-warning">{{ $stats['in_progress'] }}</h3>
            </div>
        </div>
        <div class="card border-l-4 border-success">
            <div class="p-5">
                <p class="text-sm text-gray-500">{{ __('messages.resolved') }}</p>
                <h3 class="text-3xl font-bold text-success">{{ $stats['resolved'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card">
        <div class="p-4 flex flex-wrap gap-3">
            @php
                $statuses = [
                    null => __('messages.all'),
                    'open' => __('messages.open'),
                    'in_progress' => __('messages.in_progress'),
                    'resolved' => __('messages.resolved'),
                ];
            @endphp
            @foreach($statuses as $key => $label)
                <a href="{{ route('backend.warranty-issues.index', ['project' => $project->id, 'status' => $key]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium border {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Issues Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.warranty_issues') }}</h4>
        </div>
        @if($issues->isEmpty())
            <div class="p-10 text-center">
                <i class="uil uil-file-question text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">{{ __('messages.no_warranty_issues_yet') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.issue_title') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.reported_by') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.issue_date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($issues as $issue)
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ app()->getLocale() === 'ar' ? $issue->issue_title_ar : $issue->issue_title }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $issue->reporter?->name ?? __('messages.not_available') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $issue->issue_date?->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClasses = match($issue->status) {
                                        'open' => 'bg-danger/10 text-danger',
                                        'in_progress' => 'bg-warning/10 text-warning',
                                        'resolved' => 'bg-success/10 text-success',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                    {{ __('messages.' . $issue->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('backend.warranty-issues.show', $issue) }}" class="btn btn-sm btn-light">
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
