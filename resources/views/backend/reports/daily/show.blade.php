@extends('backend.layouts.master')

@section('title', __('messages.daily_report') . ' - ' . \Carbon\Carbon::parse($report->report_date)->format('Y-m-d'))
@section('page-title', __('messages.daily_report'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ __('messages.daily_report') }} - {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">
                {{ app()->getLocale() === 'ar' ? $report->project->name_ar : $report->project->name }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.reports.daily.edit', $report) }}" class="btn btn-warning">
                <i class="uil uil-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <form method="POST"
                  action="{{ route('backend.reports.daily.destroy', $report) }}"
                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="uil uil-trash me-2"></i>
                    {{ __('messages.delete') }}
                </button>
            </form>
            <a href="{{ route('backend.reports.daily.index', $report->project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Report Details Card -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.report_details') }}</h4>
        </div>
        <div class="p-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.report_date') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.number_of_workers') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $report->worker_count }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.total_work_hours') }}</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $report->work_hours }}h</span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-sm font-medium text-gray-500">{{ __('messages.created_by') }}</span>
                        <span class="text-sm text-gray-900">{{ $report->creator->name }}</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="p-6 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.completion_percentage') }}</p>
                        <h3 class="text-4xl font-bold {{ $report->completion_percentage < 50 ? 'text-danger' : ($report->completion_percentage < 80 ? 'text-warning' : 'text-success') }} mb-4">
                            {{ number_format($report->completion_percentage, 1) }}%
                        </h3>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $report->completion_percentage < 50 ? 'bg-danger' : ($report->completion_percentage < 80 ? 'bg-warning' : 'bg-success') }}"
                                 style="width: {{ min($report->completion_percentage, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($report->notes || $report->notes_ar)
            <div class="mt-6 pt-6 border-t">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.notes') }}</h5>

                @if(app()->getLocale() === 'ar' && $report->notes_ar)
                    <p class="text-gray-600 whitespace-pre-line" dir="rtl">{{ $report->notes_ar }}</p>
                @else
                    <p class="text-gray-600 whitespace-pre-line">{{ $report->notes }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Comments Section -->
    @include('backend.partials.comments', ['commentable' => $report, 'comments' => $report->comments])
</div>
@endsection
