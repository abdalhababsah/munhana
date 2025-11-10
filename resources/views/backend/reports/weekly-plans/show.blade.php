@extends('backend.layouts.master')

@section('title', __('messages.weekly_plan') . ' - ' . (app()->getLocale() === 'ar' ? $weeklyPlan->project->name_ar : $weeklyPlan->project->name))
@section('page-title', __('messages.weekly_plan'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $weeklyPlan->project->name_ar : $weeklyPlan->project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $weeklyPlan->project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.weekly-plans.edit', $weeklyPlan) }}" class="btn btn-warning">
                <i class="uil uil-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <a href="{{ route('backend.weekly-plans.index', $weeklyPlan->project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">{{ __('messages.weekly_plan_details') }}</h4>
                <span class="text-sm text-gray-600">
                    {{ \Carbon\Carbon::parse($weeklyPlan->week_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($weeklyPlan->week_end_date)->format('M d, Y') }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.week_start_date') }}</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($weeklyPlan->week_start_date)->format('l, F d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.week_end_date') }}</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($weeklyPlan->week_end_date)->format('l, F d, Y') }}
                    </p>
                </div>
            </div>

            <!-- Planned Activities (English) -->
            <div class="mb-6">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ __('messages.planned_activities') }} ({{ __('messages.english') }})
                </h5>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="prose max-w-none">
                        <div class="whitespace-pre-line text-gray-700">{{ $weeklyPlan->planned_activities }}</div>
                    </div>
                </div>
            </div>

            <!-- Planned Activities (Arabic) -->
            @if($weeklyPlan->planned_activities_ar)
            <div class="mb-6">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ __('messages.planned_activities') }} ({{ __('messages.arabic') }})
                </h5>
                <div class="bg-gray-50 rounded-lg p-4" dir="rtl">
                    <div class="prose max-w-none">
                        <div class="whitespace-pre-line text-gray-700">{{ $weeklyPlan->planned_activities_ar }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="pt-6 border-t">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">{{ __('messages.created_by') }}:</span>
                        {{ $weeklyPlan->creator->name }}
                    </div>
                    <div>
                        <span class="font-medium">{{ __('messages.created_at') }}:</span>
                        {{ $weeklyPlan->created_at->format('Y-m-d H:i') }}
                    </div>
                    @if($weeklyPlan->updated_at != $weeklyPlan->created_at)
                    <div>
                        <span class="font-medium">{{ __('messages.updated_at') }}:</span>
                        {{ $weeklyPlan->updated_at->format('Y-m-d H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
