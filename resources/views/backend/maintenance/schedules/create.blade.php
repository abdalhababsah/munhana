@extends('backend.layouts.master')

@section('title', __('messages.add_maintenance_schedule'))
@section('page-title', __('messages.add_maintenance_schedule'))

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('messages.add_maintenance_schedule') }}</h4>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('backend.maintenance-schedules.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ __('messages.maintenance_date') }} <span class="text-danger">*</span></label>
                    <input type="date" name="maintenance_date" class="form-input w-full @error('maintenance_date') border-danger @enderror" value="{{ old('maintenance_date') }}" required>
                    @error('maintenance_date')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                    <select name="status" class="form-select w-full @error('status') border-danger @enderror" required>
                        <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>{{ __('messages.scheduled') }}</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                    </select>
                    @error('status')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ __('messages.maintenance_type') }} (EN) <span class="text-danger">*</span></label>
                    <input type="text" name="maintenance_type" class="form-input w-full @error('maintenance_type') border-danger @enderror" value="{{ old('maintenance_type') }}" required>
                    @error('maintenance_type')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.maintenance_type') }} (AR) <span class="text-danger">*</span></label>
                    <input type="text" name="maintenance_type_ar" dir="rtl" class="form-input w-full @error('maintenance_type_ar') border-danger @enderror" value="{{ old('maintenance_type_ar') }}" required>
                    @error('maintenance_type_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ __('messages.notes') }} (EN)</label>
                    <textarea name="notes" rows="4" class="form-textarea w-full @error('notes') border-danger @enderror">{{ old('notes') }}</textarea>
                    @error('notes')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.notes') }} (AR)</label>
                    <textarea name="notes_ar" rows="4" dir="rtl" class="form-textarea w-full @error('notes_ar') border-danger @enderror">{{ old('notes_ar') }}</textarea>
                    @error('notes_ar')<p class="text-sm text-danger mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-check me-2"></i>{{ __('messages.save') }}
                </button>
                <a href="{{ route('backend.maintenance-schedules.index', $project) }}" class="btn btn-light">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
