@extends('backend.layouts.master')

@section('title', __('messages.weekly_plans'))
@section('page-title', __('messages.weekly_plans'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="card">
        <div class="p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ __('messages.project') }}</p>
                <h4 class="text-2xl font-semibold text-gray-900">
                    {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
                </h4>
                <p class="text-sm text-gray-500 mt-1">{{ $project->contract_number }}</p>
            </div>
            <a href="{{ route('client.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Overview -->
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_plans') }}</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $weeklyPlans->count() }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-1">{{ __('messages.current_week_plan') }}</p>
                @php
                    $currentWeek = $weeklyPlans->first(function ($plan) {
                        return now()->between($plan->week_start_date, $plan->week_end_date);
                    });
                @endphp
                <h3 class="text-3xl font-bold text-emerald-600">{{ $currentWeek ? __('messages.yes') : __('messages.no') }}</h3>
            </div>
        </div>
    </div>

    <!-- Plans -->
    @if($weeklyPlans->isEmpty())
        <div class="card">
            <div class="p-12 text-center">
                <i class="uil uil-calendar-alt text-6xl text-gray-300 mb-4"></i>
                <h5 class="text-lg font-semibold text-gray-900">{{ __('messages.no_weekly_plans_yet') }}</h5>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach($weeklyPlans as $plan)
                @php
                    $isCurrentWeek = now()->between($plan->week_start_date, $plan->week_end_date);
                @endphp
                <div class="card {{ $isCurrentWeek ? 'border-2 border-primary' : '' }}">
                    <div class="p-6 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">
                                    {{ __('messages.week_start_date') }}: {{ $plan->week_start_date?->format('Y-m-d') }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ __('messages.week_end_date') }}: {{ $plan->week_end_date?->format('Y-m-d') }}
                                </p>
                            </div>
                            @if($isCurrentWeek)
                                <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold">
                                    {{ __('messages.current_week_plan') }}
                                </span>
                            @endif
                        </div>

                        @if($plan->planned_activities)
                        <div>
                            <h5 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.planned_activities') }} (English)</h5>
                            <p class="text-gray-700 whitespace-pre-line">{{ $plan->planned_activities }}</p>
                        </div>
                        @endif

                        @if($plan->planned_activities_ar)
                        <div>
                            <h5 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.planned_activities') }} (العربية)</h5>
                            <p class="text-gray-700 whitespace-pre-line" dir="rtl">{{ $plan->planned_activities_ar }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
