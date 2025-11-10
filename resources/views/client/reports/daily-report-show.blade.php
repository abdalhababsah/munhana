@extends('backend.layouts.master')

@section('title', __('messages.daily_report'))
@section('page-title', __('messages.daily_report'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="card">
        <div class="p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-bold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('messages.report_date') }}: {{ $report->report_date?->format('Y-m-d') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('client.reports.daily', $project) }}" class="btn btn-light">
                    <i class="uil uil-arrow-left me-2"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Report Details -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.report_details') }}</h4>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.worker_count') }}</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $report->worker_count }}</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.work_hours') }}</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($report->work_hours, 1) }}</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.completion_percentage') }}</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($report->completion_percentage, 1) }}%</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.created_by') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $report->creator?->name ?? '—' }}</p>
                </div>
            </div>

            @if($report->notes)
            <div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.description') }} (English)</h5>
                <p class="text-gray-700 whitespace-pre-line">{{ $report->notes }}</p>
            </div>
            @endif

            @if($report->notes_ar)
            <div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.description') }} (العربية)</h5>
                <p class="text-gray-700 whitespace-pre-line" dir="rtl">{{ $report->notes_ar }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Comments -->
    @include('backend.partials.comments', [
        'commentable' => $report,
        'comments' => $comments,
    ])
</div>
@endsection
