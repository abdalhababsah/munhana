@extends('backend.layouts.master')

@section('title', __('messages.add_daily_report'))
@section('page-title', __('messages.add_daily_report'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h4 class="card-title">{{ __('messages.project') }}: {{ app()->getLocale() === 'ar' ? $project->name_ar : $project->name }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-light">{{ __('messages.back') }}</a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('worker.reports.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div>
                    <label class="form-label">{{ __('messages.report_date') }}</label>
                    <input type="date" name="report_date" value="{{ old('report_date', now()->format('Y-m-d')) }}" class="form-input w-full text-lg @error('report_date') border-danger @enderror" required>
                    @error('report_date')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('messages.worker_count') }}</label>
                        <input type="number" name="worker_count" value="{{ old('worker_count') }}" class="form-input w-full text-lg @error('worker_count') border-danger @enderror" min="0" required>
                        @error('worker_count')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.work_hours') }}</label>
                        <input type="number" step="0.1" name="work_hours" value="{{ old('work_hours') }}" class="form-input w-full text-lg @error('work_hours') border-danger @enderror" min="0" required>
                        @error('work_hours')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('messages.completion_percentage') }}</label>
                    <input type="number" name="completion_percentage" value="{{ old('completion_percentage') }}" class="form-input w-full text-lg @error('completion_percentage') border-danger @enderror" min="0" max="100" required>
                    @error('completion_percentage')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('messages.description') }} (EN)</label>
                    <textarea name="notes" rows="4" class="form-textarea w-full text-lg @error('notes') border-danger @enderror">{{ old('notes') }}</textarea>
                    @error('notes')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('messages.description') }} (AR)</label>
                    <textarea name="notes_ar" rows="4" dir="rtl" class="form-textarea w-full text-lg @error('notes_ar') border-danger @enderror">{{ old('notes_ar') }}</textarea>
                    @error('notes_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-full text-lg py-3">
                    <i class="uil uil-check me-2"></i>{{ __('messages.submit') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
