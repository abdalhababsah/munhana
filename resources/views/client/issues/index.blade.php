@extends('backend.layouts.master')

@section('title', __('messages.my_issues'))
@section('page-title', __('messages.my_reported_issues'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h4 class="text-2xl font-semibold text-gray-900">{{ __('messages.my_reported_issues') }}</h4>
        <a href="{{ route('client.issues.projects') }}" class="btn btn-primary">
            <i class="uil uil-plus me-2"></i>
            {{ __('messages.report_issue') }}
        </a>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="card"><div class="p-5"><p class="text-sm text-gray-500">{{ __('messages.total_issues') }}</p><h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3></div></div>
        <div class="card border-l-4 border-danger"><div class="p-5"><p class="text-sm text-gray-500">{{ __('messages.open_issues') }}</p><h3 class="text-3xl font-bold text-danger">{{ $stats['open'] }}</h3></div></div>
        <div class="card border-l-4 border-warning"><div class="p-5"><p class="text-sm text-gray-500">{{ __('messages.in_progress') }}</p><h3 class="text-3xl font-bold text-warning">{{ $stats['in_progress'] }}</h3></div></div>
        <div class="card border-l-4 border-success"><div class="p-5"><p class="text-sm text-gray-500">{{ __('messages.resolved') }}</p><h3 class="text-3xl font-bold text-success">{{ $stats['resolved'] }}</h3></div></div>
    </div>

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
                @php $query = $key ? ['status' => $key] : []; @endphp
                <a href="{{ route('client.issues.index', $query) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium border {{ ($status === $key) ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if($issues->isEmpty())
        <div class="card">
            <div class="p-12 text-center">
                <i class="uil uil-exclamation-circle text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">{{ __('messages.no_issues_yet') }}</p>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach($groupedIssues as $projectId => $projectIssues)
                @php $project = $projectIssues->first()->project; @endphp
                @if(!$project)
                    @continue
                @endif
                <div class="card">
                    <div class="card-header"><h4 class="card-title">{{ __('messages.project') }}: {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</h4></div>
                    <div class="p-6 grid md:grid-cols-2 gap-4">
                        @foreach($projectIssues as $issue)
                            <div class="border rounded-lg p-4 flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-lg font-semibold text-gray-900">
                                        {{ app()->getLocale() === 'ar' ? $issue->issue_title_ar : $issue->issue_title }}
                                    </h5>
                                    @php
                                        $badge = match($issue->status){
                                            'open' => 'bg-danger/10 text-danger',
                                            'in_progress' => 'bg-warning/10 text-warning',
                                            'resolved' => 'bg-success/10 text-success',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ __('messages.' . $issue->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ __('messages.issue_date') }}: {{ $issue->issue_date?->format('Y-m-d') }}</p>
                                <a href="{{ route('client.issues.show', $issue) }}" class="btn btn-sm btn-primary self-end">{{ __('messages.view_details') }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
