@extends('backend.layouts.master')

@section('title', __('messages.new') . ' ' . __('messages.weekly_plan') . ' - ' . (app()->getLocale() === 'ar' ? $project->name_ar : $project->name))
@section('page-title', __('messages.new') . ' ' . __('messages.weekly_plan'))

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-xl font-semibold text-gray-900">
                {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
            </h4>
            <p class="text-sm text-gray-500 mt-1">{{ __('messages.contract_number') }}: {{ $project->contract_number }}</p>
        </div>
        <a href="{{ route('backend.weekly-plans.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.weekly_plan_details') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.weekly-plans.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Week Start Date -->
                    <div>
                        <label for="week_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.week_start_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="week_start_date"
                               name="week_start_date"
                               value="{{ old('week_start_date', $weekStart) }}"
                               class="form-input w-full @error('week_start_date') border-danger @enderror"
                               required>
                        @error('week_start_date')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.week_starts_monday') }}</p>
                    </div>

                    <!-- Week End Date -->
                    <div>
                        <label for="week_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.week_end_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="week_end_date"
                               name="week_end_date"
                               value="{{ old('week_end_date', $weekEnd) }}"
                               class="form-input w-full @error('week_end_date') border-danger @enderror"
                               required>
                        @error('week_end_date')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.week_ends_sunday') }}</p>
                    </div>
                </div>

                <!-- Planned Activities (English) -->
                <div class="mb-6">
                    <label for="planned_activities" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.planned_activities') }} ({{ __('messages.english') }}) <span class="text-danger">*</span>
                    </label>
                    <textarea id="planned_activities"
                              name="planned_activities"
                              rows="8"
                              class="form-input w-full @error('planned_activities') border-danger @enderror"
                              placeholder="{{ __('messages.planned_activities_placeholder') }}"
                              required>{{ old('planned_activities') }}</textarea>
                    @error('planned_activities')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.use_bullet_points') }}</p>
                </div>

                <!-- Planned Activities (Arabic) -->
                <div class="mb-6">
                    <label for="planned_activities_ar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.planned_activities') }} ({{ __('messages.arabic') }})
                    </label>
                    <textarea id="planned_activities_ar"
                              name="planned_activities_ar"
                              rows="8"
                              dir="rtl"
                              class="form-input w-full @error('planned_activities_ar') border-danger @enderror"
                              placeholder="{{ __('messages.planned_activities_placeholder_ar') }}">{{ old('planned_activities_ar') }}</textarea>
                    @error('planned_activities_ar')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1" dir="rtl">{{ __('messages.use_bullet_points_ar') }}</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('backend.weekly-plans.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }} {{ __('messages.weekly_plan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
