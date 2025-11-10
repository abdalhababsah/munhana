@extends('backend.layouts.master')

@section('title', __('messages.new_report'))
@section('page-title', __('messages.new_report'))

@section('content')
<div class="flex flex-col gap-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <h4 class="text-xl font-semibold text-gray-900">
            {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}
        </h4>
        <a href="{{ route('backend.reports.daily.index', $project) }}" class="btn btn-light">
            <i class="uil uil-arrow-left me-2"></i>
            {{ __('messages.back') }}
        </a>
    </div>

    <!-- Warning if report exists for today -->
    @if($existsToday)
    <div class="p-4 bg-warning/10 text-warning rounded-md flex items-center">
        <i class="uil uil-exclamation-triangle text-2xl me-3"></i>
        <div>
            <p class="font-semibold">{{ __('messages.report_exists_today') }}</p>
            <p class="text-sm">{{ __('messages.report_exists_today_message') }}</p>
        </div>
    </div>
    @endif

    <!-- Create Daily Report Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('messages.new') }} {{ __('messages.daily_report') }}</h4>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('backend.reports.daily.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Report Date -->
                    <div>
                        <label for="report_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.report_date') }} <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               id="report_date"
                               name="report_date"
                               value="{{ old('report_date', $today) }}"
                               class="form-input w-full @error('report_date') border-danger @enderror"
                               required>
                        @error('report_date')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Workers -->
                    <div>
                        <label for="worker_count" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.number_of_workers') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="worker_count"
                               name="worker_count"
                               value="{{ old('worker_count') }}"
                               min="0"
                               class="form-input w-full @error('worker_count') border-danger @enderror"
                               required>
                        @error('worker_count')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Work Hours -->
                    <div>
                        <label for="work_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.total_work_hours') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="work_hours"
                               name="work_hours"
                               value="{{ old('work_hours') }}"
                               min="0"
                               step="0.5"
                               class="form-input w-full @error('work_hours') border-danger @enderror"
                               required>
                        @error('work_hours')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Completion Percentage -->
                    <div>
                        <label for="completion_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.completion_percentage') }} <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               id="completion_percentage"
                               name="completion_percentage"
                               value="{{ old('completion_percentage') }}"
                               min="0"
                               max="100"
                               step="0.1"
                               class="form-input w-full @error('completion_percentage') border-danger @enderror"
                               required>
                        @error('completion_percentage')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes (English) -->
                    <div class="lg:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.notes') }} (English)
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="4"
                                  class="form-textarea w-full @error('notes') border-danger @enderror"
                                  placeholder="{{ __('messages.daily_work_summary') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes (Arabic) -->
                    <div class="lg:col-span-2">
                        <label for="notes_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.notes') }} (العربية)
                        </label>
                        <textarea id="notes_ar"
                                  name="notes_ar"
                                  rows="4"
                                  dir="rtl"
                                  class="form-textarea w-full @error('notes_ar') border-danger @enderror"
                                  placeholder="{{ __('messages.daily_work_summary') }}">{{ old('notes_ar') }}</textarea>
                        @error('notes_ar')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 mt-6 pt-6 border-t">
                    <button type="submit" class="btn btn-primary">
                        <i class="uil uil-check me-2"></i>
                        {{ __('messages.create') }}
                    </button>
                    <a href="{{ route('backend.reports.daily.index', $project) }}" class="btn btn-light">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
