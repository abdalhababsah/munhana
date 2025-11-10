@extends('backend.layouts.master')

@section('title', __('messages.weekly_plans') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.weekly_plans'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('backend.weekly-plans.create', $project) }}" class="btn btn-primary">
                <i class="uil uil-plus me-2"></i>
                {{ __('messages.new') }} {{ __('messages.weekly_plan') }}
            </a>
            <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-light">
                <i class="uil uil-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-1 gap-6">
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">{{ __('messages.total_plans') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_plans'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-calendar-alt text-primary text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($currentPlan)
    <div class="card border-2 border-primary">
        <div class="card-header bg-primary/5">
            <div class="flex items-center justify-between">
                <h4 class="card-title flex items-center">
                    <i class="uil uil-star text-warning text-xl me-2"></i>
                    {{ __('messages.current_week_plan') }}
                </h4>
                <span class="text-sm text-gray-600">
                    {{ \Carbon\Carbon::parse($currentPlan->week_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($currentPlan->week_end_date)->format('M d, Y') }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="prose max-w-none">
                <div class="whitespace-pre-line text-gray-700" {!! app()->getLocale() === 'ar' && $currentPlan->planned_activities_ar ? 'dir="rtl"' : '' !!}>
                    {{ app()->getLocale() === 'ar' && $currentPlan->planned_activities_ar ? $currentPlan->planned_activities_ar : $currentPlan->planned_activities }}
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t">
                <span class="text-sm text-gray-500">
                    {{ __('messages.created_by') }}: {{ $currentPlan->creator->name }} - {{ $currentPlan->created_at->format('Y-m-d') }}
                </span>
                <div class="flex gap-2">
                    <a href="{{ route('backend.weekly-plans.edit', $currentPlan) }}" class="btn btn-sm btn-warning">
                        <i class="uil uil-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.all_weekly_plans') }}</h4>
        </div>
        <div class="p-6">
            @if($plans->count() > 0)
            <div class="space-y-4">
                @foreach($plans as $plan)
                @if($currentPlan && $plan->id === $currentPlan->id)
                    @continue
                @endif
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow" x-data="{ expanded: false }">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h5 class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($plan->week_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($plan->week_end_date)->format('M d, Y') }}
                                </h5>
                                <button @click="expanded = !expanded" class="text-primary hover:text-primary/80">
                                    <i class="uil text-lg" :class="expanded ? 'uil-angle-up' : 'uil-angle-down'"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ __('messages.created_by') }}: {{ $plan->creator->name }} - {{ $plan->created_at->format('Y-m-d') }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('backend.weekly-plans.edit', $plan) }}" class="text-warning hover:text-warning/80">
                                <i class="uil uil-edit text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('backend.weekly-plans.destroy', $plan) }}"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:text-danger/80">
                                    <i class="uil uil-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div x-show="expanded" x-cloak class="mt-4 pt-4 border-t">
                        <div class="prose max-w-none">
                            <div class="whitespace-pre-line text-gray-700" {!! app()->getLocale() === 'ar' && $plan->planned_activities_ar ? 'dir="rtl"' : '' !!}>
                                {{ app()->getLocale() === 'ar' && $plan->planned_activities_ar ? $plan->planned_activities_ar : $plan->planned_activities }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <i class="uil uil-calendar-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('messages.no_data') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
